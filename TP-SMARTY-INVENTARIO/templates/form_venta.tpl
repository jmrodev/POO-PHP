{include 'header.tpl' page_title=$venta ? 'Editar Venta' : 'Registrar Venta'}

<div class="container">
    <h1>{$venta ? 'Editar Venta' : 'Registrar Nueva Venta'}</h1>

    {if $message}
        <p class="message {if $isSuccess}success{else}error{/if}">{$message}</p>
    {/if}

    <form action="{$BASE_URL}{$venta ? 'ventas/update' : 'ventas/create'}" method="POST">
        {if $venta}
            <input type="hidden" name="id" value="{$venta->getId()}">
        {/if}
        <label>Repuesto:
            <select name="repuesto_id" required>
                <option value="">Seleccione un repuesto</option>
                {foreach from=$repuestos item=repuesto}
                    <option value="{$repuesto->getId()}" {if $venta && $venta->getRepuesto() && $venta->getRepuesto()->getId() == $repuesto->getId()}selected{/if}>{$repuesto->getNombre()} (Precio: {$repuesto->getPrecio()})</option>
                {/foreach}
            </select>
        </label>
        <label>Cliente:
            <select name="cliente_id" required>
                <option value="">Seleccione un cliente</option>
                {foreach from=$clientes item=cliente}
                    <option value="{$cliente->getId()}" {if $venta && $venta->getCliente() && $venta->getCliente()->getId() == $cliente->getId()}selected{/if}>{$cliente->getNombre()} (DNI: {$cliente->getDni()})</option>
                {/foreach}
            </select>
        </label>
        <label>Cantidad:
            <input type="number" name="cantidad" value="{if $venta}{$venta->getCantidad()}{else}{/if}" required min="1">
        </label>
        <input type="submit" value="{$venta ? 'Actualizar Venta' : 'Registrar Venta'}">
    </form>

    <div class="back-link">
        <a href="{$BASE_URL}ventas">Volver al Listado de Ventas</a>
    </div>
</div>

{include 'footer.tpl'}