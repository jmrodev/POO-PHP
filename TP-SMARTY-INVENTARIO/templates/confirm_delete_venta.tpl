{include 'header.tpl'}

<div class="container">
    <h1>Confirmar Eliminación de Venta</h1>

    <div class="card">
        <div class="card-header">
            <h2>¿Está seguro de que desea eliminar esta venta?</h2>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {$venta->getId()}</p>
            <p><strong>Repuesto:</strong> {$venta->getRepuesto()->getNombre()}</p>
            <p><strong>Usuario:</strong> {$venta->getUsuario()->getNombre()}</p>
            <p><strong>Cantidad:</strong> {$venta->getCantidad()}</p>
            <p><strong>Fecha:</strong> {$venta->getFecha()}</p>
        </div>
        <div class="card-footer">
            <form method="POST" action="{$BASE_URL}ventas/delete_confirm/{$venta->getId()}">
                <button type="submit" class="confirm-button">Confirmar Eliminación</button>
            </form>
            <a href="{$BASE_URL}ventas" class="cancel-button">Cancelar</a>
        </div>
    </div>
</div>

{include 'footer.tpl'}
