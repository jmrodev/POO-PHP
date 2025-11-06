
<div class="container">
    <h1>Confirmar Eliminación de Venta</h1>

    <p>¿Está seguro de que desea eliminar la siguiente venta?</p>

    <p><strong>ID:</strong> {$venta->getId()}</p>
    <p><strong>Repuesto:</strong> {$venta->getRepuesto()->getNombre()}</p>
    <p><strong>Cliente:</strong> {$venta->getCliente()->getNombre()}</p>
    <p><strong>Cantidad:</strong> {$venta->getCantidad()}</p>
    <p><strong>Fecha:</strong> {$venta->getFecha()}</p>

    <div class="modal-buttons">
        <a href="{$BASE_URL}ventas/delete/{$venta->getId()}" class="confirm-button">Confirmar Eliminación</a>
        <a href="{$BASE_URL}ventas" class="cancel-button">Cancelar</a>
    </div>
</div>

{include 'footer.tpl'}
