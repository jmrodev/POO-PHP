{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>{$page_title}</h1>

    {if $smarty.session.role == 'admin'}
        <div class="menu-options">
            <a href="{$BASE_URL}usuarios/add" class="menu-button">Añadir Nuevo Usuario</a>
        </div>
    {/if}

    {if !empty($personas)}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>DNI</th>
                    <th>Rol</th>
                    {if $smarty.session.role == 'admin'}
                        <th>Acciones</th>
                    {/if}
                </tr>
            </thead>
            <tbody>
                {foreach $personas as $persona}
                    <tr>
                        <td>{$persona->getId()}</td>
                        <td>{$persona->getNombre()}</td>
                        <td>{$persona->getUsername()}</td>
                        <td>{if $persona->getRole() == 'user' || $persona->getRole() == 'supervisor'}{$persona->getDni()}{else}N/A{/if}</td>
                        <td>{$persona->getRole()}</td>
                        {if $smarty.session.role == 'admin'}
                            <td>
                                <div class="action-buttons">
                                    <a href="{$BASE_URL}usuarios/edit/{$persona->getId()}" class="edit-button">Editar</a>
                                    <a href="{$BASE_URL}usuarios/delete/{$persona->getId()}" class="delete-button">Eliminar</a>
                                </div>
                            </td>
                        {/if}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p>No hay usuarios registrados.</p>
    {/if}
    
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>
{/block}