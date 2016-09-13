<?php

include_once './global_declarations.php';

class User {

    public $Login, $PWD, $Age, $About, $Photos, $email;
    public $Meta;

    public function __construct() {
        $this->Login = "";
        $this->Age = "";
        $this->About = "";
        $this->Photos = Array();
    }

}

class HT4Model {

    const HOST = "127.0.0.1";
    const USER = "root";
    const PWD = "";
    const DB = "ht-4";

    private $con;

    public function __construct() {
        $this->con = new mysqli(self::HOST, self::USER, self::PWD, self::DB);
    }

    public function __destruct() {
        $this->con->close();
    }

    public function getUserInfo($login) {
        $user_data_qry = "SELECT * FROM `user` WHERE `NAME` = '$login'";
        $photos_qry = "SELECT * FROM `photos` WHERE `NAME` = '$login'";

        $userDataResult = $this->con->query($user_data_qry);
        if (mysqli_num_rows($userDataResult) == 0) {
            return false;
        }

        $user_data = mysqli_fetch_assoc($userDataResult);
        $photos = $this->con->query($photos_qry);

        $user = new User;
        $user->Login = $login;
        $user->Age = $user_data["AGE"];
        $user->About = $user_data["NOTE"];
        $user->PWD = $user_data["PWD"];

        while ($row = mysqli_fetch_row($photos)) {
            array_push($user->Photos, $row[2]);
        }

        return $user;
    }

    public function getUsersList() {
        $query = "SELECT * FROM `user` ORDER BY `AGE`";
        $result = $this->con->query($query);
        $lst = [];
        while ($row = $result->fetch_row()) {
            $user = new User();
            $user->Login = $row[1];
            $user->Age = $row[2];
            $user->About = $row[3];
            array_push($lst, $user);
        }

        return $lst;
    }

    public function addUser($login, $pwd, $age, $about, $photo, $email) {
        $q1 = "INSERT INTO `user`(`NAME`, `AGE`, `NOTE`, `PWD`) "
                . "VALUES ('$login', '$age', '$about', '$pwd')";
        $res = $this->con->query($q1);
        if (!$res) {
            echo "Ошибка базы данных. <br>"
            . "Не удалось добавить пользователя $login";
            toTheMainPage();
            return false;
        }

        $uploaded = move_uploaded_file($photo["tmp_name"], "./photos/" . $photo["name"]);
        if (!$uploaded) {
            echo "Не удалось загрузить фотографию";
        }

        $filename = $photo["name"];
        $q2 = "INSERT INTO `photos` (name, filename) VALUES ('$login', '$filename')";
        $this->con->query($q2);

        return true;
    }
    
    public function addPhoto($login, $filename) {
        $q = "INSERT INTO `photos` (name, filename) VALUES ('$login', '$filename')";
        return $this->con->query($q);
    }

    public function deletePhoto($login, $filename) {
	$q = "DELETE FROM `photos` WHERE `NAME` = '$login' AND `FILENAME` = '$filename'";
	return $this->con->query($q);
    }

}
