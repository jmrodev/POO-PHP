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
                        <td>{$pedido->getEstado()}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{$BASE_URL}pedidos/detail/{$pedido->getId()}" class="detail-button">Ver Detalle</a>
                                {if $smarty.session.role == 'admin' || $smarty.session.role == 'supervisor' || ($smarty.session.role == 'user' && $pedido->getUsuarioId() == $smarty.session.user_id && $pedido->getEstado() == 'pendiente')}
                                    <a href="{$BASE_URL}pedidos/edit/{$pedido->getId()}" class="edit-button">Editar</a>
                                    <a href="{$BASE_URL}pedidos/delete/{$pedido->getId()}" class="delete-button">Cancelar</a>
                                {/if}
                            </div>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p>No hay pedidos registrados.</p>
    {/if}
    
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>
{/block}
