<?php

include_once "./twig/Autoloader.php";

abstract class Controller_Base {

    protected $twig;

    public function __construct() {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem("./views");
        $twig = new Twig_Environment($loader/* , ['cache' => './twig_cache'] */);
        $this->twig = $twig;
    }

    abstract function index();
}
