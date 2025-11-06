
<div class="container">
    <h1>Confirmar Eliminación de Cliente</h1>

    <p>¿Está seguro de que desea eliminar el siguiente cliente?</p>

    <p><strong>ID:</strong> {$cliente->getId()}</p>
    <p><strong>Nombre:</strong> {$cliente->getNombre()}</p>
    <p><strong>DNI:</strong> {$cliente->getDni()}</p>

    <div class="modal-buttons">
        <a href="{$BASE_URL}clientes/delete/{$cliente->getId()}" class="confirm-button">Confirmar Eliminación</a>
        <a href="{$BASE_URL}clientes" class="cancel-button">Cancelar</a>
    </div>
</div>

{include 'footer.tpl'}
