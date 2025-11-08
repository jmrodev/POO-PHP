{include 'header.tpl'}

<div class="container">
    <h1>Detalle de la Venta</h1>

    <div class="card">
        <div class="card-header">
            <h2>Venta #{$venta->getId()}</h2>
        </div>
        <div class="card-body">
            <p><strong>Repuesto:</strong> {$venta->getRepuesto()->getNombre()}</p>
            <p><strong>Usuario:</strong> {$venta->getUsuario()->getNombre()}</p>
            <p><strong>Cantidad:</strong> {$venta->getCantidad()}</p>
            <p><strong>Fecha:</strong> {$venta->getFecha()}</p>
            <p><strong>Precio Unitario:</strong> {$venta->getRepuesto()->getPrecio()}</p>
            <p><strong>Total:</strong> {$venta->getCantidad() * $venta->getRepuesto()->getPrecio()}</p>
        </div>
        <div class="card-footer">
            <a href="{$BASE_URL}ventas/edit/{$venta->getId()}" class="edit-button">Editar Venta</a>
            <a href="{$BASE_URL}ventas" class="back-button">Volver al Listado</a>
        </div>
    </div>
</div>

{include 'footer.tpl'}
