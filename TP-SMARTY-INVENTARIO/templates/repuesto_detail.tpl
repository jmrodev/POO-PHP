{include 'header.tpl' page_title='Detalle del Repuesto'}

<div class="container">
    <h1>Detalle del Repuesto</h1>

    <div class="card">
        <div class="card-header">
            <h2>{$repuesto->getNombre()}</h2>
        </div>
        <div class="card-body">
            {if $repuesto->getImagen()}
                <img src="data:image/jpeg;base64,{$repuesto->getImagen()}" alt="Imagen del Repuesto" class="card-img">
            {else}
                <div class="no-image">No hay imagen disponible</div>
            {/if}
            <p><strong>ID:</strong> {$repuesto->getId()}</p>
            <p><strong>Precio:</strong> {$repuesto->getPrecio()}</p>
            <p><strong>Cantidad:</strong> {$repuesto->getCantidad()}</p>
        </div>
        <div class="card-footer">
            <a href="{$BASE_URL}repuestos/edit/{$repuesto->getId()}" class="edit-button">Editar Repuesto</a>
            <a href="{$BASE_URL}repuestos" class="back-button">Volver al Listado</a>
        </div>
    </div>
</div>

{include 'footer.tpl'}