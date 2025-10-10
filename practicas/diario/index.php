<?php
require_once 'datos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Diario Digital</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <header>
        <h1>Diario del Programador</h1>
    </header>

    <main class="diario-container">
        <?php
        if (!empty($noticias)) {
            foreach ($noticias as $noticia) {
                echo '<a href="noticia.php?id=' . htmlspecialchars($noticia['id']) . '" class="noticia-card">';
                echo '    <img src="' . htmlspecialchars($noticia['imagen_url']) . '" alt="Imagen de la noticia">';
                echo '    <div class="noticia-content">';
                echo '        <h2>' . htmlspecialchars($noticia['titulo']) . '</h2>';
                // Usamos substr para mostrar solo los primeros 100 caracteres del párrafo como resumen
                echo '        <p>' . htmlspecialchars(substr($noticia['parrafo'], 0, 100)) . '...</p>';
                echo '    </div>';
                echo '</a>';
            }
        } else {
            echo "<p>No hay noticias para mostrar.</p>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Mi Diario Digital</p>
    </footer>

</body>
</html>
