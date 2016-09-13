<?php

require "./controllers/base.php";
require './models/ht4Model.php';

class Controller_UserEditor extends Controller_Base {

    public function index() {
        return null;
    }

    public function showAddPhotoForm() {
        session_start();
        if (isset($_SESSION["user"])) {
            echo $this->twig
                    ->render("AddPhotoForm.html", ["action" => ROOT . "/UserEditor/addPhoto"]);
        }
        else {
            echo "Вы не авторизованы <br>";
            toTheMainPage();
        }
    }

    public function addPhoto() {
        $photo = $_FILES["photo"];
        if (!empty($photo)) {
            $is_replaced = move_uploaded_file($photo["tmp_name"], "./photos/" . $photo['name']);
            if ($is_replaced) {
                session_start();
                $m = new HT4Model();
                $is_added = $m->addPhoto($_SESSION["user"], $photo["name"]);
            }
            if (!$is_added) {
                echo "Оштбка базы данных. Фотография не добавлена. <br>";
            }
            else {
                echo "Фотография успешно добавлена <br>";
            }
            toTheMainPage();
        }
    }

    public function showDelPhotoPage() {
        session_start();
        if (isset($_SESSION["user"])) {
            $login = $_SESSION["user"];
            $m = new HT4Model();
            $user = $m->getUserInfo($login);
            $photos = array_map(
                function ($fn) {
                    return ROOT . "/photos/$fn";
		}, $user->Photos);
            echo $this
                    ->twig
                    ->render("DelPhotoView.html",
                            ["photos" => $user->Photos,
                            "action" => ROOT . "/userEditor/delPhotos"]);
        }
        else {
            $this->notAuthorized();
        }
    }

    public function delPhotos() {
        session_start();
        if (isset($_SESSION["user"])) {
            $login = $_SESSION["user"];
            $m = new ht4Model();
            foreach ($_POST as $key => $value) {
                if ($value == "on") {
		    $fn = preg_replace("/_jpg_cb/", ".jpg", $key);
                    $db_res = $m->deletePhoto($login, $fn);
                    if ($db_res) {
                        $file_res = unlink("./photos/$fn");
                        echo "Фотография $fn удалена <br>";
                        toTheMainPage();
                    }
                    else {
			echo "Не удалось удалить фотографию $fn <br>";
			toTheMainPage();
			return;
                    }
                }
            }
        }
        else {
            $this->notAuthorized();
        }
    }

    private function notAuthorized() {
	echo "Вы не авторизованы <br>";
        toTheMainPage();
    }

}
