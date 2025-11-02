{include 'header.tpl' page_title='Gestión de Repuestos'}

<div class="container">
    <h1>Listado de Repuestos</h1>

    <div class="menu-options">
        <a href="{$BASE_URL}repuestos/create" class="menu-button">Crear Nuevo Repuesto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$repuestos item=repuesto}
                <tr>
                    <td>{$repuesto->getId()}</td>
                    <td>{$repuesto->getNombre()}</td>
                    <td>{$repuesto->getPrecio()}</td>
                    <td>{$repuesto->getCantidad()}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{$BASE_URL}repuestos/edit/{$repuesto->getId()}" class="edit-button">Editar</a>
                            <a href="{$BASE_URL}repuestos/confirmdelete/{$repuesto->getId()}" class="delete-button">Eliminar</a>
                        </div>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="5">No hay repuestos registrados.</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>

{include 'footer.tpl'}