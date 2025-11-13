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

    <div class="main-catalog-area">
        <div class="product-catalog">
            {foreach from=$repuestos item=repuesto}
                <div class="product-card">
                    {if $repuesto->getImagen()}
                        <img src="data:image/jpeg;base64,{$repuesto->getImagen()}" alt="{$repuesto->getNombre()}" class="product-image">
                    {else}
                        <img src="{$BASE_URL}img/placeholder.png" alt="No Image" class="product-image"> {* Placeholder image *}
                    {/if}
                    <h3 class="product-name">{$repuesto->getNombre()}</h3>
                    <p class="product-price">Precio: ${$repuesto->getPrecio()|number_format:2}</p>
                    <p class="product-stock">Stock: {$repuesto->getCantidad()}</p>
                    
                    {if $repuesto->getCantidad() > 0}
                        <form action="{$BASE_URL}cart/add" method="post" class="add-to-cart-form">
                            <input type="hidden" name="repuesto_id" value="{$repuesto->getId()}">
                            <input type="number" name="cantidad" value="1" min="1" max="{$repuesto->getCantidad()}" class="quantity-input">
                            <button type="submit" class="add-to-cart-button">Añadir al Carrito</button>
                        </form>
                    {else}
                        <p class="out-of-stock">Sin Stock</p>
                    {/if}
                </div>
            {foreachelse}
                <p>No hay repuestos disponibles en el catálogo.</p>
            {/foreach}
        </div>

        {if !empty($oferta_repuestos)}
            <aside class="offer-aside">
                <h2>Ofertas Especiales</h2>
                {foreach from=$oferta_repuestos item=oferta}
                    <div class="offer-card">
                        {if $oferta->getImagen()}
                            <img src="data:image/jpeg;base64,{$oferta->getImagen()}" alt="{$oferta->getNombre()}" class="offer-image">
                        {else}
                            <img src="{$BASE_URL}img/placeholder.png" alt="No Image" class="offer-image">
                        {/if}
                        <h4 class="offer-name">{$oferta->getNombre()}</h4>
                        <p class="offer-price">Precio: ${$oferta->getPrecio()|number_format:2}</p>
                        <a href="{$BASE_URL}repuestos/detail/{$oferta->getId()}" class="view-offer-button">Ver Detalle</a>
                    </div>
                {/foreach}
            </aside>
        {/if}
    </div>
    <div class="back-link">
        <a href="{$BASE_URL}home">Volver al Inicio</a>
    </div>
</div>
{/block}
