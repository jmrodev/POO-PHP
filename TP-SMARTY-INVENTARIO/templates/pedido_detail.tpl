{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>Detalle del Pedido #{$pedido->getId()}</h1>

    <div class="pedido-detail">
        <p><strong>Usuario:</strong> {$pedido->getUsuario()->getNombre()}</p>
        <p><strong>Fecha del Pedido:</strong> {$pedido->getFechaPedido()}</p>
        <p><strong>Estado:</strong> {$pedido->getEstado()}</p>
        <p><strong>Total:</strong> ${$pedido->getTotal()|number_format:2}</p>

        <h2>Ítems del Pedido</h2>
        {if !empty($pedido->getDetalles())}
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$pedido->getDetalles() item=detalle}
                        <tr>
                            <td>{$detalle->getRepuesto()->getNombre()}</td>
                            <td>
                                {if $detalle->getRepuesto()->getImagen()}
                                    <img src="data:image/jpeg;base64,{$detalle->getRepuesto()->getImagen()}" alt="{$detalle->getRepuesto()->getNombre()}" width="50">
                                {else}
                                    No Image
                                {/if}
                            </td>
                            <td>{$detalle->getCantidad()}</td>
                            <td>${$detalle->getPrecioUnitario()|number_format:2}</td>
                            <td>${($detalle->getCantidad() * $detalle->getPrecioUnitario())|number_format:2}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {else}
            <p>Este pedido no tiene ítems.</p>
        {/if}
    </div>

    <div class="back-link">
        <a href="{$BASE_URL}pedidos">Volver al Listado de Pedidos</a>
    </div>
</div>
{/block}
