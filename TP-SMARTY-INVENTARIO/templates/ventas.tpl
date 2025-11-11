{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Listado de Ventas</h1>

    {if $smarty.session.user_id && !$authService->isSupervisor()}
        <div class="menu-options">
            <a href="{$BASE_URL}ventas/add" class="menu-button">Registrar Nueva Venta</a>
        </div>
    {/if}

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Repuesto</th>
                <th>Usuario</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$ventas item=venta}
                <tr>
                    <td>{$venta->getId()}</td>
                    <td>{$venta->getRepuesto()->getNombre()}</td>
                    <td>{$venta->getUsuario()->getNombre()}</td>
                    <td>{$venta->getCantidad()}</td>
                    <td>{$venta->getFecha()}</td>
                    <td>
                        <div class="action-buttons">
                            {if $smarty.session.role == 'admin' || $smarty.session.role == 'supervisor' || ($smarty.session.role == 'user' && $venta->getUsuario()->getId() == $smarty.session.user_id)}
                                <a href="{$BASE_URL}ventas/edit/{$venta->getId()}" class="edit-button">Editar</a>
                            {/if}
                            <a href="{$BASE_URL}ventas/detail/{$venta->getId()}" class="detail-button">Ver Detalle</a>
                            {if $smarty.session.role == 'admin' || $smarty.session.role == 'supervisor' || ($smarty.session.role == 'user' && $venta->getUsuario()->getId() == $smarty.session.user_id)}
                                <a href="{$BASE_URL}ventas/delete/{$venta->getId()}" class="delete-button">Eliminar</a>
                            {/if}
                        </div>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="6">No hay ventas registradas.</td>
                </tr>
            {/foreach}
                    </tbody>
                </table>
        
            {if $totalPages > 1}
                {include 'pagination.tpl' baseURL=$baseURL currentPage=$currentPage totalPages=$totalPages}
            {/if}
        
            <div class="back-link">
                <a href="{$BASE_URL}home">Volver al Inicio</a>
            </div>
        </div>
        {/block}
