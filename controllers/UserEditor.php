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

}
