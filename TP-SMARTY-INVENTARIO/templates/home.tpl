{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1 class="main-title">Bienvenido al Sistema de Inventario</h1>
    <p class="subtitle">Seleccione una opción para comenzar:</p>
    <div class="main-content-area">
            <div class="menu-options">
                {if $authService->isAdmin()}
                    <a href="{$BASE_URL}usuarios" class="menu-button">Gestión de Usuarios</a>
                {/if}
                {if $authService->isSupervisor()}
                    <a href="{$BASE_URL}repuestos" class="menu-button">Gestión de Repuestos</a>
                {/if}
                {if $authService->isSupervisor()} {* Only supervisors can access legacy sales *}
                    <a href="{$BASE_URL}ventas" class="menu-button">Gestión de Ventas (Legacy)</a>
                {/if}
                {if $authService->isUser()}
                    <a href="{$BASE_URL}catalog" class="menu-button">Catálogo de Productos</a>
                {/if}
                {if $authService->isUser() || $authService->isSupervisor()}
                    <a href="{$BASE_URL}pedidos" class="menu-button">Gestión de Pedidos</a>
                {/if}
            </div>
            {if $authService->isAdmin() && !empty($user_summary)}
                <aside class="admin-summary-aside">
                    <h2>Resumen de Usuarios</h2>
                    <ul>
                        <li>Total de Personas: {$user_summary.total_users}</li>
                        <li>Administradores: {$user_summary.admin_count}</li>
                        <li>Supervisores: {$user_summary.supervisor_count}</li>
                        <li>Clientes/Usuarios: {$user_summary.client_count}</li>
                    </ul>
                </aside>
            {/if}    </div>
</div>
{/block}
