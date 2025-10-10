{include file='header.tpl' titulo=$noticia->titulo}

<div class="noticia-full-container">
    {if $noticia}
        <div class="contenido-completo">
        <a href="{$base_url}index.php" class="volver-btn">Volver al Inicio</a>
    {else}
        <h1>Noticia no encontrada</h1>
        <p>La noticia que buscas no existe.</p>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    {/if}
</div>

{include file='footer.tpl'}
