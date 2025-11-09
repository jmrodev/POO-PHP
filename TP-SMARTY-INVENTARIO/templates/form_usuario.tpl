{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>{$page_title}</h1>

    {if isset($error_message)}
        <div class="alert alert-danger">
            <p>{$error_message}</p>
        </div>
    {/if}

    <form action="{$form_action}" method="post">
        {if $is_edit}
            <input type="hidden" name="id" value="{$usuario->getId()}">
        {/if}

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{$form_data.nombre|default:$usuario->getNombre()}" required>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" class="form-control" id="dni" name="dni" value="{$form_data.dni|default:$usuario->getDni()}" required>
        </div>
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" value="{$form_data.username|default:$usuario->getUsername()}" required>
        </div>
        {if !$is_edit}
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        {/if}
        {if $is_admin}
            <div class="form-group">
                <label for="role">Rol:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user" {if $usuario && $usuario->getRole() == 'user'}selected{/if}>Usuario</option>
                    <option value="supervisor" {if $usuario && $usuario->getRole() == 'supervisor'}selected{/if}>Supervisor</option>
                </select>
            </div>
        {/if}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{$BASE_URL}usuarios" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
{/block}