<?php

include_once "./global_declarations.php";
include_once "base.php";
include_once "./models/ht4Model.php";

class Controller_UserSelector extends Controller_Base {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = new HT4Model();
    }

    public function index() {
        echo "QueryDB_Contoller";
    }

    public function selectUser($login) {
        $user = $this->db->getUserInfo($login);
        return $user;
    }

    public function showUserPhotos() {
        session_start();
        if (isset($_SESSION["user"])) {
            $login = $_SESSION["user"];
            $user = $this->selectUser($login);

            $join_dir = function ($fn) {
                return ROOT . "/photos/" . $fn;
            };

            $photos = array_map($join_dir, $user->Photos);
            $t = $this->twig->loadTemplate("users_photos.html");

            echo $t->render(["photos" => $photos]);
            toTheMainPage();
        }
        else {
            echo "Вы не авторизованы <br>";
            toTheMainPage();
        }
    }

    public function showUsersList() {
        session_start();
        if (isset($_SESSION["user"])) {
            $lst = $this->db->getUsersList();
            foreach ($lst as $user) {
                if ($user->Age >= 18) {
                    $user->Meta = "Совершеннолетний";
                }
                else {
                    $user->Meta = "Несовершеннолетний";
                }
            }

            $cmp = function (User $user1, User $user2) {
                return $user1->Login > $user2->Login;
            };
            usort($lst, $cmp);
            echo $this->twig->render("ShowListOfUsersView.html", ["lst" => $lst]);
        }
        else {
            echo "Вы не авторизованы";
        }
    }

}
