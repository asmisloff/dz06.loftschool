<?php

include "base.php";
include_once './global_declarations.php';

class Controller_Index extends Controller_Base {

    public function index() {
        echo $this->twig->render("ShowIndexView.html");
    }

}
