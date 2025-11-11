{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Editar Pedido #{$pedido->getId()}</h1>

    {if isset($smarty.session.error_message)}
        <div class="alert alert-danger">{$smarty.session.error_message}</div>
        {$smarty.session.error_message = null} {* Clear the message after displaying *}
    {/if}

    <form action="{$form_action}" method="post">
        <input type="hidden" name="id" value="{$pedido->getId()}">

        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" value="{if $pedido->getUsuario()}{$pedido->getUsuario()->getNombre()}{else}{''}{/if}" disabled class="form-control">
        </div>

        <div class="form-group">
            <label for="fecha_pedido">Fecha del Pedido:</label>
            <input type="text" id="fecha_pedido" value="{$pedido->getFechaPedido()}" disabled class="form-control">
        </div>

        <div class="form-group">
            <label for="total">Total:</label>
            <input type="text" id="total" value="${$pedido->getTotal()|number_format:2}" disabled class="form-control">
        </div>

        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" class="form-control" {if $smarty.session.role == 'user' && $pedido->getEstado() != 'pendiente'}disabled{/if}>
                <option value="pendiente" {if $pedido->getEstado() == 'pendiente'}selected{/if}>Pendiente</option>
                {if $smarty.session.role == 'admin' || $smarty.session.role == 'supervisor'}
                    <option value="completado" {if $pedido->getEstado() == 'completado'}selected{/if}>Completado</option>
                    <option value="cancelado" {if $pedido->getEstado() == 'cancelado'}selected{/if}>Cancelado</option>
                {else}
                    {* User can only change to 'cancelado' if pending *}
                    {if $pedido->getEstado() == 'pendiente'}
                        <option value="cancelado" {if $pedido->getEstado() == 'cancelado'}selected{/if}>Cancelar Pedido</option>
                    {/if}
                {/if}
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{$BASE_URL}pedidos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
{/block}
