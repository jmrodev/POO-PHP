{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>{$page_title}</h1>

    {if isset($smarty.session.success_message)}
        <div class="alert alert-success">{$smarty.session.success_message}</div>
        {$smarty.session.success_message = null} {* Clear the message after displaying *}
    {/if}
    {if isset($smarty.session.error_message)}
        <div class="alert alert-danger">{$smarty.session.error_message}</div>
        {$smarty.session.error_message = null} {* Clear the message after displaying *}
    {/if}

    {if !empty($pedidos)}
        <table class="pedidos-table">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$pedidos item=pedido}
                    <tr>
                        <td>{$pedido->getId()}</td>
                        <td>{$pedido->getUsuario()->getNombre()}</td>
                        <td>{$pedido->getFechaPedido()}</td>
                        <td>${$pedido->getTotal()|number_format:2}</td>
                        <td>
                            {if $authService->isAdmin() || $authService->isSupervisor()}
                                <form action="{$BASE_URL}pedidos/update" method="post" class="status-form">
                                    <input type="hidden" name="id" value="{$pedido->getId()}">
                                    <select name="estado" onchange="this.form.submit()">
                                        <option value="pendiente" {if $pedido->getEstado() == 'pendiente'}selected{/if}>Pendiente</option>
                                        <option value="completado" {if $pedido->getEstado() == 'completado'}selected{/if}>Completado</option>
                                        <option value="cancelado" {if $pedido->getEstado() == 'cancelado'}selected{/if}>Cancelado</option>
                                    </select>
                                </form>
                            {elseif $authService->isUser() && $pedido->getUsuarioId() == $smarty.session.user_id && $pedido->getEstado() == 'pendiente'}
                                <form action="{$BASE_URL}pedidos/update" method="post" class="status-form">
                                    <input type="hidden" name="id" value="{$pedido->getId()}">
                                    <select name="estado" onchange="this.form.submit()">
                                        <option value="pendiente" {if $pedido->getEstado() == 'pendiente'}selected{/if}>Pendiente</option>
                                        <option value="cancelado" {if $pedido->getEstado() == 'cancelado'}selected{/if}>Cancelado</option>
                                    </select>
                                </form>
                            {else}
                                {$pedido->getEstado()}
                            {/if}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{$BASE_URL}pedidos/detail/{$pedido->getId()}" class="detail-button">Ver Detalle</a>
                                {if $authService->isAdmin() || $authService->isSupervisor()}
                                    <a href="{$BASE_URL}pedidos/edit/{$pedido->getId()}" class="edit-button">Editar</a>
                                {/if}
                                {if $authService->isUser() && $pedido->getUsuarioId() == $smarty.session.user_id && $pedido->getEstado() == 'pendiente'}
                                    <a href="{$BASE_URL}pedidos/delete/{$pedido->getId()}" class="delete-button">Cancelar</a>
                                {elseif $authService->isAdmin() || $authService->isSupervisor()}
                                    <a href="{$BASE_URL}pedidos/delete/{$pedido->getId()}" class="delete-button">Eliminar</a> {* Admins/Supervisors can delete any order *}
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
        <p>No hay pedidos registrados.</p>
    {/if}
    
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>
{/block}
