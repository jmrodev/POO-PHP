{include 'header.tpl'}

<div class="container">
    <h1>{if $is_edit}Editar Repuesto{else}Crear Nuevo Repuesto{/if}</h1>

    {if isset($message)}
        <p class="message {if $isSuccess}success{else}error{/if}">{$message}</p>
    {/if}

    <form action="{$form_action}" method="POST" enctype="multipart/form-data">
        {if $repuesto && $repuesto->getId()}
            <input type="hidden" name="id" value="{$repuesto->getId()}">
        {/if}
        <label>Nombre:
            <input type="text" name="nombre" value="{if $repuesto}{$repuesto->getNombre()}{/if}" required>
        </label>
        <label>Precio:
            <input type="number" name="precio" value="{if $repuesto}{$repuesto->getPrecio()}{/if}" step="0.01" required>
        </label>
        <label>Cantidad:
            <input type="number" name="cantidad" value="{if $repuesto}{$repuesto->getCantidad()}{/if}" required>
        </label>
        <label>Imagen:
            <input type="file" name="imagen">
            {if $repuesto && $repuesto->getImagen()}
                <img src="data:image/jpeg;base64,{$repuesto->getImagen()}" alt="Imagen del Repuesto" width="100">
            {/if}
        </label>
        <input type="submit" value="{if $is_edit}Actualizar{else}Crear{/if}">
    </form>

    <div class="back-link">
        <a href="{$BASE_URL}repuestos">Volver al Listado de Repuestos</a>
    </div>
</div>

{include 'footer.tpl'}
