<?php

function pprint($arr) {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function println($s) {
    echo "$s<br>";
}

function a($action, $text) {
    echo "<a href=" . ROOT . "?route=$action > $text </a> <br>";
}

function toTheMainPage() {
    echo "<a href=" . ROOT . "> На главную </a> <br>";
}
