<?php

    function crearTabla () {
        $tabla = [];

        for ($lin = 0; $lin < 5; $lin++)
        {
            $linea = [];
            for ($col = 0; $col < 4; $col++) {
                $linea[] = random_int(1, 100);
            }
            $tabla[] = $linea;
        }

        return $tabla;
    }

    function mostrarTabla($tabla) {
        echo ("<table border='1'>");
        foreach ($tabla as $linea) {
            echo ("<tr>");
            foreach ($linea as $col) {
                echo ("<td>");
                echo $col;
                echo ("</td>");
            }
            echo ("</tr>");
        }
     
        echo ("</table>");
    }

    $t = crearTabla();
    mostrarTabla($t);


