{include 'header.tpl' page_title='Confirmar Eliminación de Repuesto'}

<div class="container">
    <h1>Confirmar Eliminación de Repuesto</h1>

    <p>¿Está seguro de que desea eliminar el siguiente repuesto?</p>

    <p><strong>ID:</strong> {$repuesto->getId()}</p>
    <p><strong>Nombre:</strong> {$repuesto->getNombre()}</p>
    <p><strong>Precio:</strong> {$repuesto->getPrecio()}</p>
    <p><strong>Cantidad:</strong> {$repuesto->getCantidad()}</p>

    <div class="modal-buttons">
        <a href="{$BASE_URL}repuestos/delete/{$repuesto->getId()}" class="confirm-button">Confirmar Eliminación</a>
        <a href="{$BASE_URL}repuestos" class="cancel-button">Cancelar</a>
    </div>
</div>

{include 'footer.tpl'}