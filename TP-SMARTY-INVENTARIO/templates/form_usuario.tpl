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

        {* Prepare usuario data for default values *}
        {assign var="usuario_nombre" value=""}
        {assign var="usuario_dni" value=""}
        {assign var="usuario_username" value=""}
        {assign var="selected_role" value=""} {* Initialize selected_role *}

        {if isset($usuario)}
            {assign var="usuario_nombre" value=$usuario->getNombre()}
            {assign var="usuario_dni" value=$usuario->getDni()}
            {assign var="usuario_username" value=$usuario->getUsername()}
            {assign var="selected_role" value=$usuario->getRole()} {* Assign role if usuario exists *}
        {/if}

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{$form_data.nombre|default:$usuario_nombre}" required>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" class="form-control" id="dni" name="dni" value="{$form_data.dni|default:$usuario_dni}" required pattern="^\d{8}$" maxlength="8" minlength="8" title="El DNI debe contener 8 dígitos numéricos.">
        </div>
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" value="{$form_data.username|default:$usuario_username}" required>
        </div>
        {if !$is_edit}
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        {/if}
        {if $authService->isAdmin()}
            <div class="form-group">
                <label for="role">Rol:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user" {if $selected_role == 'user'}selected{/if}>Usuario</option>
                    <option value="supervisor" {if $selected_role == 'supervisor'}selected{/if}>Supervisor</option>
                    <option value="admin" {if $selected_role == 'admin'}selected{/if}>Administrador</option>
                </select>
            </div>
        {/if}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{$BASE_URL}usuarios" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
{/block}