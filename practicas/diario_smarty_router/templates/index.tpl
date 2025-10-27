{include file='header.tpl' titulo='Inicio del Diario'}

<div class="diario-container">
    {foreach from=$noticias item=noticia}
        <a href="{$base_url}index.php?action=noticia&id={$noticia->id}" class="noticia-card">
            <img src="{$noticia->imagen}" alt="{$noticia->titulo}" class="noticia-imagen">
            <div class="noticia-content">
                <h2>{$noticia->titulo}</h2>
                <p>{$noticia->contenido|truncate:100:"..."}</p>
            </div>
        </a>
    {/foreach}
</div>

{include file='footer.tpl'}