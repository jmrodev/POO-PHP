{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h2>Registro de Usuario</h2>

    {if isset($error_message)}
        <div class="alert alert-danger">
            <p>{$error_message}</p>
        </div>
    {/if}

    <form action="{$BASE_URL}register" method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required value="{$form_data.nombre|default:''}" maxlength="255">
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" class="form-control" id="dni" name="dni" required value="{$form_data.dni|default:''}" 
           
        </div>
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required value="{$form_data.username|default:''}" maxlength="255">
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required minlength="6" maxlength="255">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" maxlength="255">
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <p>¿Ya tienes una cuenta? <a href="{$BASE_URL}login">Inicia sesión aquí</a>.</p>
</div>
{/block}
