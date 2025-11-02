{include 'header.tpl' page_title=$repuesto ? 'Editar Repuesto' : 'Crear Repuesto'}

<div class="container">
    <h1>{$repuesto ? 'Editar Repuesto' : 'Crear Nuevo Repuesto'}</h1>

    {if $message}
        <p class="message {if $isSuccess}success{else}error{/if}">{$message}</p>
    {/if}

    <form action="{$BASE_URL}{$repuesto ? 'repuestos/update' : 'repuestos/create'}" method="POST">
        {if $repuesto}
            <input type="hidden" name="id" value="{$repuesto->getId()}">
        {/if}
        <label>Nombre:
            <input type="text" name="nombre" value="{if $repuesto}{$repuesto->getNombre()}{else}{/if}" required>
        </label>
        <label>Precio:
            <input type="number" name="precio" value="{if $repuesto}{$repuesto->getPrecio()}{else}{/if}" step="0.01" required>
        </label>
        <label>Cantidad:
            <input type="number" name="cantidad" value="{if $repuesto}{$repuesto->getCantidad()}{else}{/if}" required>
        </label>
        <input type="submit" value="{$repuesto ? 'Actualizar' : 'Crear'}">
    </form>

    <div class="back-link">
        <a href="{$BASE_URL}repuestos">Volver al Listado de Repuestos</a>
    </div>
</div>

{include 'footer.tpl'}