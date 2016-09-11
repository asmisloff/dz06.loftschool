<?php

require "./phpmailer/PHPMailerAutoload.php";

class Mailer extends PHPMailer {

    public function __construct() {
        parent::__construct();
        $this->isSMTP();
        $this->Host = 'smtp.yandex.ru';
        $this->SMTPAuth = true;
        $this->Username = 'a.smisloff@yandex.ru';
        $this->Password = 'Gfccdjhl113';
        $this->SMTPSecure = 'tls';
        $this->Port = 587;
        $this->CharSet = "utf-8";
        $this->setFrom("a.smisloff@ya.ru", "Система авторизации ht-6");
        $this->isHTML(true);
    }

    public function prepareLetter($email, $subject, $body) {
        if (!self::validateAddress($email)) {
            $this->ErrorInfo = "email is not valid -- $email <br>";
            return false;
        }
        $this->addAddress($email);
        $this->Subject = $subject;
        $this->Body = $body;
        return true;
    }

}