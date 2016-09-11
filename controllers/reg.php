<?php

include_once "./classes/Recaptcha_Checker.php";
include_once "./controllers/base.php";
include_once "./models/ht4Model.php";
include_once "./Mailer.php";
include_once "./twig/Autoloader.php";

class Controller_Reg extends Controller_Base {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        return null;
    }

    public function showRegForm() {
        $t = $this->twig->loadTemplate("reg.html");
        $vars = array("loginVarName" => "login",
            "pwdVarName" => "pwd",
            "ageVarName" => "age",
            "aboutVarName" => "about",
            "photoVarName" => "photo",
            "emailVarName" => "email",
            "action" => ROOT . "/reg/registerUser");
        echo $t->render($vars);
    }

    public function registerUser($loginVarName, $pwdVarName, $ageVarName, $aboutVarName, $photoVarName, $emailVarName) {
        $login = filter_input(INPUT_POST, $loginVarName, FILTER_SANITIZE_STRING);
        $pwd = filter_input(INPUT_POST, $pwdVarName, FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, $ageVarName, FILTER_VALIDATE_INT);
        $about = filter_input(INPUT_POST, $aboutVarName, FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, $emailVarName, FILTER_SANITIZE_EMAIL);
        $photo = $_FILES[$photoVarName];
        $resp = filter_input(INPUT_POST, "g-recaptcha-response");
        
        if (Recaptcha_Checker::verify($resp)) {
            if (!$this->is_image($photo)) {
                echo "$photo is not an image <br>";
                toTheMainPage();
                return;
            }

            $m = new HT4Model();
            $user = $m->getUserInfo($login);
            if (!$user) {
                $res = $m->addUser($login, $pwd, $age, $about, $photo, $email);
                if ($res) {
                    echo "Добавлен пользователь $login <br>";
                    toTheMainPage();
                }
                if ($email != "") {
                    $m = new Mailer();
                    $subject = "Подтверждение регистрации";
                    $body = "Вы зарегистрированы, $login";
                    $m->prepareLetter($email, $subject, $body);
                    $m->send();
                }
            }
            else {
                echo "Пользователь $login уже существует <br>";
                toTheMainPage();
            }
        }
        else {
            echo "Неверная капча <br>";
            toTheMainPage();
        }
    }

    private function is_image($file) {
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            return true;
        }
        else {
            return false;
        }
    }

}
