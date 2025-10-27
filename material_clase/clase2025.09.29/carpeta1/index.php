<html>
  <head>
    <title></title>
    <meta content="">
    <style></style>
 <!-- Latest compiled and minified CSS -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>     
  </head>
  <body>
<?php
    $arcquitecturas = [
        'Tiempo real',
        'Capas',
        'Cliente Servidor'
    ]
    ;

    echo "<h1>Arquitecturas</h1>";

    echo "<ul>";
    foreach ($arcquitecturas as $arqui) {
        echo "<li>";
        echo $arqui;
        echo "</li>";
    }
    echo "</ul>";

    echo "<select>";
    foreach ($arcquitecturas as $arqui) {
        echo "<option>";
        echo $arqui;
        echo "</option>";
    }
    echo "</select>";
?>
</body>
</html>