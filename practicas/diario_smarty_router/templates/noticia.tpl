{include file='header.tpl' titulo=$noticia->titulo|default:'Noticia no encontrada'}

<div class="noticia-full-container">
    {if $noticia}
        <div class="contenido-completo">
        <img src="{$noticia->imagen}" alt="{$noticia->titulo}" class="noticia-imagen-full">
        <h2>{$noticia->titulo}</h2>
        <p>{$noticia->contenido}</p>
        <a href="{$base_url}index.php" class="volver-btn">Volver al Inicio</a>
    {else}
        <h1>Noticia no encontrada</h1>
        <p>La noticia que buscas no existe.</p>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    {/if}
</div>

{include file='footer.tpl'}
