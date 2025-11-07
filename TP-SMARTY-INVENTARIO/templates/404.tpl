<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:"404 No Encontrado"}</title>
    <link rel="stylesheet" href="{$BASE_URL}css/style.css">
</head>
<body>
    <div class="container">
        <h2>{$title|default:"404 Página No Encontrada"}</h2>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <p><a href="{$BASE_URL}">Volver a la página de inicio</a></p>
    </div>
</body>
</html>
