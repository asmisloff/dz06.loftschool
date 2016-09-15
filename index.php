<?php

include './includes/startup.php';
include_once './global_declarations.php';

$router = new Router();
$router->setPath(site_path . "controllers");
$router->delegate();