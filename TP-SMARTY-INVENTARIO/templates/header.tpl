<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$page_title|default:"Inventario"}</title>
    <link rel="stylesheet" href="{$BASE_URL}css/style.css">
    <link rel="stylesheet" href="{$BASE_URL}css/cart_styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                {if $authService->isLoggedIn()}
                    <li>Bienvenido, {$authService->getUsername()}</li>
                    {if $authService->isAdmin()}
                        <li><a href="{$BASE_URL}usuarios">Gestionar Usuarios</a></li>
                    {else}
                        <li><a href="{$BASE_URL}cart">Ver Carrito</a></li>
                        <li><a href="{$BASE_URL}pedidos">Mis Pedidos</a></li>
                    {/if}
                    <li><a href="{$BASE_URL}logout">Cerrar Sesión</a></li>
                {else}
                    <li><a href="{$BASE_URL}login">Iniciar Sesión</a></li>
                {/if}
            </ul>
        </nav>
    </header>
