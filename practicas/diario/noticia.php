<?php
require_once 'datos.php';

$id_noticia = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$noticia_encontrada = null; 

foreach ($noticias as $noticia) {
    if ($noticia['id'] === $id_noticia) {
        $noticia_encontrada = $noticia;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noticia_encontrada ? htmlspecialchars($noticia_encontrada['titulo']) : 'Noticia no encontrada'; ?></title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <main class="noticia-full-container">
        <?php
        if ($noticia_encontrada) {
            echo '<h1>' . htmlspecialchars($noticia_encontrada['titulo']) . '</h1>';
            echo '<img src="' . htmlspecialchars($noticia_encontrada['imagen_url']) . '" alt="Imagen de la noticia">';
            echo '<p class="contenido-completo">' . nl2br(htmlspecialchars($noticia_encontrada['parrafo'])) . '</p>';
        } else {
            echo '<h1>Error 404</h1>';
            echo '<p>Lo sentimos, la noticia que buscas no existe o fue eliminada.</p>';
        }
        ?>
        <a href="index.php" class="volver-btn">← Volver al inicio</a>
    </main>

</body>
</html>
