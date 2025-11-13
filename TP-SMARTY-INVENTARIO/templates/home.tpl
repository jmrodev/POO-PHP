{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <div class="home-header">
        <h1 class="home-title">Bienvenido al Sistema de Inventario</h1>
        <p class="home-subtitle">Seleccione una opción para comenzar</p>
    </div>

    <div class="home-layout">
        <div class="menu-cards">
            {if $authService->isAdmin()}
                <a href="{$BASE_URL}usuarios" class="menu-card admin-card">
                    <span class="menu-card-icon material-icons">group</span>
                    <h3 class="menu-card-title">Usuarios</h3>
                    <p class="menu-card-description">Gestionar usuarios del sistema</p>
                </a>
            {/if}
            
            {if $authService->isSupervisor()}
                <a href="{$BASE_URL}repuestos" class="menu-card primary-card">
                    <span class="menu-card-icon material-icons">build</span>
                    <h3 class="menu-card-title">Repuestos</h3>
                    <p class="menu-card-description">Administrar inventario de repuestos</p>
                </a>
            {/if}
            
            {if $authService->isSupervisor()}
                <a href="{$BASE_URL}ventas" class="menu-card secondary-card">
                    <span class="menu-card-icon material-icons">attach_money</span>
                    <h3 class="menu-card-title">Ventas</h3>
                    <p class="menu-card-description">Sistema de ventas (Legacy)</p>
                </a>
            {/if}
            
            {if $authService->isUser()}
                <a href="{$BASE_URL}catalog" class="menu-card success-card">
                    <span class="menu-card-icon material-icons">shopping_cart</span>
                    <h3 class="menu-card-title">Catálogo</h3>
                    <p class="menu-card-description">Explorar productos disponibles</p>
                </a>
            {/if}
            
            {if $authService->isUser() || $authService->isSupervisor()}
                <a href="{$BASE_URL}pedidos" class="menu-card info-card">
                    <span class="menu-card-icon material-icons">inventory_2</span>
                    <h3 class="menu-card-title">Pedidos</h3>
                    <p class="menu-card-description">Gestionar pedidos del sistema</p>
                </a>
            {/if}
        </div>

        {if $authService->isAdmin() && !empty($user_summary)}
            <aside class="home-summary-aside">
                <h2><span class="material-icons" style="vertical-align: middle;">bar_chart</span> Resumen del Sistema</h2>
                <div class="summary-stats">
                    <div class="stat-item">
                        <span class="stat-value">{$user_summary.total_users}</span>
                        <span class="stat-label">Total Usuarios</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{$user_summary.admin_count}</span>
                        <span class="stat-label">Administradores</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{$user_summary.supervisor_count}</span>
                        <span class="stat-label">Supervisores</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{$user_summary.client_count}</span>
                        <span class="stat-label">Clientes</span>
                    </div>
                </div>
            </aside>
        {/if}
    </div>
</div>
{/block}
