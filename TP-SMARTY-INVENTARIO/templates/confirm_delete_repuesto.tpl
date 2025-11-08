{include 'header.tpl'}

<div class="container">
    <h1>Confirmar Eliminación de Repuesto</h1>

    <div class="card">
        <div class="card-header">
            <h2>¿Está seguro de que desea eliminar este repuesto?</h2>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {$repuesto->getId()}</p>
            <p><strong>Nombre:</strong> {$repuesto->getNombre()}</p>
            <p><strong>Precio:</strong> {$repuesto->getPrecio()}</p>
            <p><strong>Cantidad:</strong> {$repuesto->getCantidad()}</p>
        </div>
        <div class="card-footer">
            <form method="POST" action="{$BASE_URL}repuestos/delete_confirm/{$repuesto->getId()}" style="display: inline;">
                <button type="submit" class="confirm-button" style="border: none; cursor: pointer;">Confirmar Eliminación</button>
            </form>
            <a href="{$BASE_URL}repuestos" class="cancel-button">Cancelar</a>
        </div>
    </div>
</div>

{include 'footer.tpl'}
