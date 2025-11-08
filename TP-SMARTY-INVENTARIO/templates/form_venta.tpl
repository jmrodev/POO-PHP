{include 'header.tpl'}

<div class="container">
    <h1>{if $is_edit}Editar Venta{else}Registrar Nueva Venta{/if}</h1>

    {if isset($message)}
        <p class="message {if $isSuccess}success{else}error{/if}">{$message}</p>
    {/if}

    <form action="{$form_action}" method="POST">
        {if $venta && $venta->getId()}
            <input type="hidden" name="id" value="{$venta->getId()}">
        {/if}
        <label>Repuesto:
            <select name="repuesto_id" required>
                <option value="">Seleccione un repuesto</option>
                {foreach from=$repuestos item=repuesto}
                    <option value="{$repuesto->getId()}" {if $venta && $venta->getRepuesto() && $venta->getRepuesto()->getId() == $repuesto->getId()}selected{/if}>{$repuesto->getNombre()} (Precio: {$repuesto->getPrecio()}) (Stock: {$repuesto->getCantidad()})</option>
                {/foreach}
            </select>
        </label>
        <label>Usuario:
        <select name="usuario_id" required>
        <option value="">Seleccione un usuario</option>
        {foreach from=$usuarios item=usuario}
        <option value="{$usuario->getId()}" {if $venta && $venta->getUsuario() && $venta->getUsuario()->getId() == $usuario->getId()}selected{/if}>{$usuario->getNombre()} (DNI: {$usuario->getDni()})</option>                {/foreach}
            </select>
        </label>
        <label>Cantidad:
            <input type="number" name="cantidad" value="{if $venta && $venta->getCantidad()}{$venta->getCantidad()}{/if}" required min="1">
        </label>
        <input type="submit" value="{if $is_edit}Actualizar Venta{else}Registrar Venta{/if}">
    </form>

    <div class="back-link">
        <a href="{$BASE_URL}ventas">Volver al Listado de Ventas</a>
    </div>
</div>

{include 'footer.tpl'}
