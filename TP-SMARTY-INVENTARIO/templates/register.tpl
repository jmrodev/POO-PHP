
<div class="container">
    <h2>Registro de Cliente</h2>

    {if $errors}
        <div class="alert alert-danger">
            <ul>
                {foreach $errors as $error}
                    <li>{$error}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <form action="{$BASE_URL}register" method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" class="form-control" id="dni" name="dni" required>
        </div>
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <p>¿Ya tienes una cuenta? <a href="{$BASE_URL}login">Inicia sesión aquí</a>.</p>
</div>

{include file='footer.tpl'}
