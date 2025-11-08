<?php

function sumar ($x, $y) {

    return $x + $y;
}

function multiplicar ($x, $y) {

    return $x * $y;
}

    $a = $_REQUEST['x'];
    $b = $_REQUEST['y'];
    $c = $_REQUEST['op'];

    if ($c == "sumar") {
        echo (sumar($a, $b));
    } else if ($c == "mult") {
        echo (multiplicar($a, $b));
    } else {
        echo ("error");
    }