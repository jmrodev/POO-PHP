<?php
/* Smarty version 5.5.2, created on 2025-10-08 11:34:44
  from 'file:noticia.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.2',
  'unifunc' => 'content_68e64c549739f5_38306108',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09beb4367a1d9ecb0236821ed1a0a38743a665aa' => 
    array (
      0 => 'noticia.tpl',
      1 => 1759923280,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_68e64c549739f5_38306108 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/jmro/Documentos/TECDA/SEGUNDO/POO/diario_smarty/templates';
$_smarty_tpl->renderSubTemplate('file:header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('titulo'=>$_smarty_tpl->getValue('noticia_encontrada')['titulo']), (int) 0, $_smarty_current_dir);
?>

<div class="noticia-full-container">
    <?php if ($_smarty_tpl->getValue('noticia_encontrada')) {?>
        <img src="<?php echo $_smarty_tpl->getValue('noticia_encontrada')['imagen_url'];?>
" alt="Imagen de la noticia">
        <div class="contenido-completo">
            <p><?php echo $_smarty_tpl->getValue('noticia_encontrada')['parrafo'];?>
</p>
        </div>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    <?php } else { ?>
        <h1>Noticia no encontrada</h1>
        <p>La noticia que buscas no existe.</p>
        <a href="index.php" class="volver-btn">Volver al Inicio</a>
    <?php }?>
</div>

<?php $_smarty_tpl->renderSubTemplate('file:footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
