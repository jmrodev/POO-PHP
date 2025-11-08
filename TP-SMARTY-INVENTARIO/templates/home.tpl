{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1 class="main-title">Bienvenido al Sistema de Inventario</h1>
    <p class="subtitle">Seleccione una opción para comenzar:</p>
    <div class="menu-options">
        <a href="{$BASE_URL}usuarios" class="menu-button">Gestión de Usuarios</a>
        <a href="{$BASE_URL}repuestos" class="menu-button">Gestión de Repuestos</a>
        <a href="{$BASE_URL}ventas" class="menu-button">Gestión de Ventas</a>
    </div>
</div>
{/block}
