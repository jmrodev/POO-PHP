{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Iniciar Sesión</h1>

    {if isset($error_message)}
        <p class="message error">{$error_message}</p>
    {/if}

    <form action="{$BASE_URL}login" method="POST">
        <label>Usuario: <input type="text" name="username" required></label>
        <br>
        <label>Contraseña: <input type="password" name="password" required></label>
        <br>
        <input type="submit" value="Entrar">
    </form>

    <p>¿No tienes cuenta? <a href="{$BASE_URL}register">Regístrate aquí</a></p>
</div>
{/block}
