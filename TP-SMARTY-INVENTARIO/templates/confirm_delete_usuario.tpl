{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>{$page_title}</h1>

    <div class="card">
        <div class="card-header">
            <h2>¿Está seguro de que desea eliminar este usuario?</h2>
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {$usuario->getNombre()}</p>
            <p><strong>ID:</strong> {$usuario->getId()}</p>
            <p class="no-image">Esta acción no se puede deshacer.</p>
        </div>
        <div class="card-footer">
            <form action="{$BASE_URL}usuarios/delete_confirm/{$usuario->getId()}" method="post">
                <button type="submit" class="confirm-button">Sí, Eliminar</button>
            </form>
            <a href="{$BASE_URL}usuarios" class="cancel-button">Cancelar</a>
        </div>
    </div>
</div>
{/block}