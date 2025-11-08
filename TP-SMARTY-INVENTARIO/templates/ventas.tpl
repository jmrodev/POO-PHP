{include 'header.tpl'}

<div class="container">
    <h1>Listado de Ventas</h1>

    <div class="menu-options">
        <a href="{$BASE_URL}ventas/add" class="menu-button">Registrar Nueva Venta</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Repuesto</th>
                <th>Usuario</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$ventas item=venta}
                <tr>
                    <td>{$venta->getId()}</td>
                    <td>{$venta->getRepuesto()->getNombre()}</td>
                    <td>{$venta->getUsuario()->getNombre()}</td>
                    <td>{$venta->getCantidad()}</td>
                    <td>{$venta->getFecha()}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{$BASE_URL}ventas/edit/{$venta->getId()}" class="edit-button">Editar</a>
                            <a href="{$BASE_URL}ventas/detail/{$venta->getId()}" class="detail-button">Ver Detalle</a>
                            <a href="{$BASE_URL}ventas/delete/{$venta->getId()}" class="delete-button">Eliminar</a>
                        </div>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="6">No hay ventas registradas.</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>

{include 'footer.tpl'}
