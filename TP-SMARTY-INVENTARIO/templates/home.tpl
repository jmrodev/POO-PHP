{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1 class="main-title">Bienvenido al Sistema de Inventario</h1>
    <p class="subtitle">Seleccione una opción para comenzar:</p>
    <div class="menu-options">
        {if $smarty.session.role == 'admin'}
            <a href="{$BASE_URL}usuarios" class="menu-button">Gestión de Usuarios</a>
        {/if}
        {if $smarty.session.role == 'admin' || $smarty.session.role == 'supervisor'}
            <a href="{$BASE_URL}repuestos" class="menu-button">Gestión de Repuestos</a>
        {/if}
        {if $smarty.session.user_id} {* All logged-in users can access sales, though with different permissions *}
            <a href="{$BASE_URL}ventas" class="menu-button">Gestión de Ventas</a>
            <a href="{$BASE_URL}catalog" class="menu-button">Catálogo de Productos</a>
        {/if}
    </div>
</div>
{/block}
