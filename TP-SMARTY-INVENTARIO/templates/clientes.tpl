{include 'header.tpl' page_title='Gestión de Clientes'}

<div class="container">
    <h1>Listado de Clientes</h1>

    <div class="menu-options">
        <a href="{$BASE_URL}clientes/create" class="menu-button">Crear Nuevo Cliente</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$clientes item=cliente}
                <tr>
                    <td>{$cliente->getId()}</td>
                    <td>{$cliente->getNombre()}</td>
                    <td>{$cliente->getDni()}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{$BASE_URL}clientes/edit/{$cliente->getId()}" class="edit-button">Editar</a>
                            <a href="{$BASE_URL}clientes/confirmdelete/{$cliente->getId()}" class="delete-button">Eliminar</a>
                        </div>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="4">No hay clientes registrados.</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>

{include 'footer.tpl'}