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
            DEBUG: Current Role is {$smarty.session.role} <br> {* DEBUG LINE *}
            {if $smarty.session.role == 'user'}
                <p class="form-control-static">{$pedido->getEstado()|capitalize}</p>
                <input type="hidden" name="estado" value="{$pedido->getEstado()}"> {* Keep hidden input for form submission, though backend prevents change *}
            {else}
                <select name="estado" id="estado" class="form-control">
                    <option value="pendiente" {if $pedido->getEstado() == 'pendiente'}selected{/if}>Pendiente</option>
                    <option value="completado" {if $pedido->getEstado() == 'completado'}selected{/if}>Completado</option>
                    <option value="cancelado" {if $pedido->getEstado() == 'cancelado'}selected{/if}>Cancelado</option>
                </select>
            {/if}
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{$BASE_URL}pedidos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
{/block}
