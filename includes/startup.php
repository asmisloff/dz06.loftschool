<?php

error_reporting(E_ALL);

define('DIRSEP', DIRECTORY_SEPARATOR);
$site_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
define('site_path', $site_path);
define("ROOT", "http://dz06.loftschool");

function __autoload($class_name) {
    $filename = strtolower($class_name) . '.php';
    $file = site_path . 'classes' . DIRSEP . $filename;
    if (!file_exists($file)) {
        return false;
    }
    include ($file);
}
