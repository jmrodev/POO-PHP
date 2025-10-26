<?php

    $usuario = $_REQUEST['nombre'];
    $pass = $_REQUEST['clave'];

    if (($usuario == 'luis') && ($pass == "1234")) {
        echo "Clave correcta";
    } else {
        echo "Clave incorrecta";
    }