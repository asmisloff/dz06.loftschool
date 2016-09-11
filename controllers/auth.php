<?php

include './controllers/base.php';
include './models/ht4Model.php';

class Controller_Auth extends Controller_Base {

    public function index() {
        return null;
    }

    public function showLogInForm() {
        echo $this->twig->render("LogInFormView.html", ["action" => ROOT . "/auth/logIn"]);
    }

    public function logIn() {
        $login = $_POST["login"];
        $pwd = $_POST["pwd"];
        $m = new HT4Model();
        $user = $m->getUserInfo($login);
        if (!$user) {
            echo "Пользователь $login не существует";
            $_SESSION["user"] = null;
        }
        elseif ($user->PWD == $pwd) {
            session_start();
            $_SESSION["user"] = $login;
        }
        else {
            $_SESSION["user"] = null;
        }
        echo "Привет, $login <br>";
        toTheMainPage();
    }

    public function logOut() {
        session_start();
        if (isset($_SESSION["user"])) {
            $login = $_SESSION["user"];
            session_destroy();
            session_abort();
            echo "Пока, $login <br>";
        }
        toTheMainPage();
    }

}
