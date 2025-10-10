{include file='header.tpl' titulo='Inicio del Diario'}

<div class="diario-container">
    {foreach from=$noticias item=noticia}
        <a href="noticia.php?id={$noticia.id}" class="noticia-card">
            <img src="{$noticia.imagen_url}" alt="Imagen de la noticia">
            <div class="noticia-content">
                <h2>{$noticia.titulo}</h2>
                <p>{$noticia.parrafo|truncate:100:"..."}</p>
            </div>
        </a>
    {/foreach}
</div>

{include file='footer.tpl'}