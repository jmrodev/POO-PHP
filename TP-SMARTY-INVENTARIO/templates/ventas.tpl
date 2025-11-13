{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Listado de Ventas (Legacy)</h1>

    {if isset($smarty.session.success_message)}
        <div class="alert alert-success">{$smarty.session.success_message}</div>
        {$smarty.session.success_message = null}
    {/if}
    {if isset($smarty.session.error_message)}
        <div class="alert alert-danger">{$smarty.session.error_message}</div>
        {$smarty.session.error_message = null}
    {/if}

    {if $authService->isSupervisor()}
        <div class="menu-options">
            <a href="{$BASE_URL}ventas/add" class="menu-button">Registrar Nueva Venta</a>
        </div>
    {/if}

    {if !empty($ventas)}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Repuesto</th>
                    <th>Cliente</th>
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
                                <a href="{$BASE_URL}ventas/detail/{$venta->getId()}" class="detail-button">Ver Detalle</a>
                                {if $authService->isSupervisor()}
                                    <a href="{$BASE_URL}ventas/edit/{$venta->getId()}" class="edit-button">Editar</a>
                                    <a href="{$BASE_URL}ventas/delete/{$venta->getId()}" class="delete-button">Eliminar</a>
                                {/if}
                            </div>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

        {if $totalPages > 1}
            {include 'pagination.tpl' baseURL=$baseURL currentPage=$currentPage totalPages=$totalPages}
        {/if}
    {else}
        <div class="alert alert-info">
            <p><strong>No hay ventas registradas en el sistema.</strong></p>
            <p>Este es el módulo legacy de ventas. Para registrar ventas, los usuarios deben realizar pedidos a través del catálogo.</p>
            {if $authService->isSupervisor()}
                <p>También puedes <a href="{$BASE_URL}ventas/add">registrar una venta manualmente</a>.</p>
            {/if}
        </div>
    {/if}

    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>
{/block}
