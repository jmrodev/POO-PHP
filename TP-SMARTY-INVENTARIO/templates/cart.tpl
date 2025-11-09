{extends 'layout.tpl'}

{block name="content"}
<div class="container">
    <h1>{$page_title}</h1>

    {if isset($smarty.session.success_message)}
        <div class="alert alert-success">{$smarty.session.success_message}</div>
        {$smarty.session.success_message = null} {* Clear the message after displaying *}
    {/if}
    {if isset($smarty.session.error_message)}
        <div class="alert alert-danger">{$smarty.session.error_message}</div>
        {$smarty.session.error_message = null} {* Clear the message after displaying *}
    {/if}

    {if !empty($cart_items)}
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$cart_items item=item}
                    <tr>
                        <td>{$item.nombre}</td>
                        <td>
                            {if $item.imagen}
                                <img src="data:image/jpeg;base64,{$item.imagen}" alt="{$item.nombre}" width="50">
                            {else}
                                No Image
                            {/if}
                        </td>
                        <td>${$item.precio|number_format:2}</td>
                        <td>
                            <form action="{$BASE_URL}cart/update" method="post" class="update-cart-form">
                                <input type="hidden" name="repuesto_id" value="{$item.id}">
                                <input type="number" name="cantidad" value="{$item.cantidad}" min="0" class="quantity-input">
                                <button type="submit" class="update-quantity-button">Actualizar</button>
                            </form>
                        </td>
                        <td>${($item.precio * $item.cantidad)|number_format:2}</td>
                        <td>
                            <a href="{$BASE_URL}cart/remove/{$item.id}" class="remove-from-cart-button">Eliminar</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total del Carrito: ${$cart_total|number_format:2}</h3>
            <a href="{$BASE_URL}cart/checkout" class="checkout-button">Proceder al Pago</a>
        </div>
    {else}
        <p>Tu carrito de compras está vacío.</p>
    {/if}

    <div class="back-link">
        <a href="{$BASE_URL}catalog">Continuar Comprando</a>
    </div>
</div>
{/block}
