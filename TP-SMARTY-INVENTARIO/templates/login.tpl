
<div class="container">
    <h1>Iniciar Sesión</h1>

    {if $message}
        <p class="message error">{$message}</p>
    {/if}

    <form action="{$BASE_URL}authenticate" method="POST">
        <label>Usuario: <input type="text" name="username" required></label>
        <br>
        <label>Contraseña: <input type="password" name="password" required></label>
        <br>
        <input type="submit" value="Entrar">
    </form>

    <p>¿No tienes cuenta? <a href="{$BASE_URL}register">Regístrate aquí</a></p>
</div>

{include 'footer.tpl'}
