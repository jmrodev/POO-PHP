{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Confirmar Cancelación de Pedido</h1>

    <div class="alert alert-warning">
        <p>¿Estás seguro de que deseas cancelar el pedido #{$pedido->getId()}?</p>
        <p>Esta acción no se puede deshacer.</p>
    </div>

    <p><strong>Usuario:</strong> {$pedido->getUsuario()->getNombre()}</p>
    <p><strong>Fecha del Pedido:</strong> {$pedido->getFechaPedido()}</p>
    <p><strong>Total:</strong> ${$pedido->getTotal()|number_format:2}</p>
    <p><strong>Estado Actual:</strong> {$pedido->getEstado()}</p>

    <form action="{$BASE_URL}pedidos/delete_confirm/{$pedido->getId()}" method="post">
        <button type="submit" class="btn btn-danger">Sí, Cancelar Pedido</button>
        <a href="{$BASE_URL}pedidos" class="btn btn-secondary">No, Volver</a>
    </form>
</div>
{/block}
