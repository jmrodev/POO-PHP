{include file='header.tpl' titulo=$noticia_encontrada.titulo}

<div class="noticia-full-container">
    {if $noticia_encontrada}
        <img src="{$noticia_encontrada.imagen_url}" alt="Imagen de la noticia">
        <div class="contenido-completo">
            <p>{$noticia_encontrada.parrafo}</p>
        </div>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    {else}
        <h1>Noticia no encontrada</h1>
        <p>La noticia que buscas no existe.</p>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    {/if}
</div>

{include file='footer.tpl'}
