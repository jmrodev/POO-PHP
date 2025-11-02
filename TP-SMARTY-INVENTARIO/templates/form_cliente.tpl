{include 'header.tpl' page_title=$cliente ? 'Editar Cliente' : 'Crear Cliente'}

<div class="container">
    <h1>{$cliente ? 'Editar Cliente' : 'Crear Nuevo Cliente'}</h1>

    {if $message}
        <p class="message {if $isSuccess}success{else}error{/if}">{$message}</p>
    {/if}

    <form action="{$BASE_URL}{$cliente ? 'clientes/update' : 'clientes/create'}" method="POST">
        {if $cliente}
            <input type="hidden" name="id" value="{$cliente->getId()}">
        {/if}
        <label>Nombre:
            <input type="text" name="nombre" value="{if $cliente}{$cliente->getNombre()}{else}{/if}" required>
        </label>
        <label>DNI:
            <input type="text" name="dni" value="{if $cliente}{$cliente->getDni()}{else}{/if}" required>
        </label>
        <input type="submit" value="{$cliente ? 'Actualizar' : 'Crear'}">
    </form>

    <div class="back-link">
        <a href="{$BASE_URL}clientes">Volver al Listado de Clientes</a>
    </div>
</div>

{include 'footer.tpl'}