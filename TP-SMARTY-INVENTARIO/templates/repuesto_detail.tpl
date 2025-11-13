
{include 'header.tpl'}

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
            <p><strong>Precio:</strong> ${$repuesto->getPrecio()|number_format:2}</p>
            <p><strong>Stock:</strong> {$repuesto->getCantidad()}</p>
        </div>
        <div class="card-footer">
            {if $authService->isSupervisor()}
                <a href="{$BASE_URL}repuestos/edit/{$repuesto->getId()}" class="edit-button">Editar Repuesto</a>
                <a href="{$BASE_URL}repuestos" class="back-button">Volver al Listado</a>
            {else}
                {if $repuesto->getCantidad() > 0}
                    <form action="{$BASE_URL}cart/add" method="post" style="display: inline-block; margin: 0;">
                        <input type="hidden" name="repuesto_id" value="{$repuesto->getId()}">
                        <input type="number" name="cantidad" value="1" min="1" max="{$repuesto->getCantidad()}" class="quantity-input" style="margin-right: 0.5rem;">
                        <button type="submit" class="add-to-cart-button">Añadir al Carrito</button>
                    </form>
                {else}
                    <p class="out-of-stock">Sin Stock</p>
                {/if}
                <a href="{$BASE_URL}catalog" class="back-button">Volver al Catálogo</a>
            {/if}
        </div>
    </div>
</div>

{include 'footer.tpl'}
