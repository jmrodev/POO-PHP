<?php
  require_once("bd.php");
 
  $id = $_REQUEST['id'];

  $index = 0;
  while ($db[$index]['id'] != $id) {
    $index ++;
  };
  if ($index < count($db)) {
    $noticia = $db[$index];
  } else {
    die('Noticia no encontrada');
  }
  
  include_once("templates/header.html");
?>
<body>
    <?php include_once('templates/menu.html'); ?>
    <main class="container mt-5">
        <section class="noticias">

            <?php

                echo ("<div class=\"card\">");
                echo ("<img src=\"".$noticia['img']."\" class=\"card-img-top\" alt=\"...\">");

                echo ("<div class=\"card-body\">");
                echo ("<h5 class=\"card-title\">".$noticia['title']."</h5>");
                echo ("<p class=\"card-text\">".$noticia['text']."</p>");
                echo ("<a href=\"noticia.php?id=".$noticia['id']."\" class=\"btn btn-outline-primary\">Leer más</a>");
                echo ("</div>");

                echo ("</div>");

            ?>
             
          </section>
          
      </main>
<?php
  include_once('templates/footer.html');
?>