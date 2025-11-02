<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$page_title|default:"Inventario"}</title>
    <link rel="stylesheet" href="{$BASE_URL}css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                {if isset($smarty.session.user_id)}
                    <li>Bienvenido, {$smarty.session.username}</li>
                    <li><a href="{$BASE_URL}logout">Cerrar Sesión</a></li>
                {else}
                    <li><a href="{$BASE_URL}login">Iniciar Sesión</a></li>
                {/if}
            </ul>
        </nav>
    </header>
