<?php
/* Smarty version 5.5.2, created on 2025-10-08 10:41:46
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.2',
  'unifunc' => 'content_68e63fead3fb16_59427433',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b02ad15c06cd78bc82fc455cb604dd5dcde7bdcb' => 
    array (
      0 => 'index.tpl',
      1 => 1759920042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_68e63fead3fb16_59427433 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/jmro/Documentos/TECDA/SEGUNDO/POO/diario_smarty/templates';
$_smarty_tpl->renderSubTemplate('file:header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('titulo'=>'Inicio del Diario'), (int) 0, $_smarty_current_dir);
?>

<div class="diario-container">
    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('noticias'), 'noticia');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('noticia')->value) {
$foreach0DoElse = false;
?>
        <a href="noticia.php?id=<?php echo $_smarty_tpl->getValue('noticia')['id'];?>
" class="noticia-card">
            <img src="<?php echo $_smarty_tpl->getValue('noticia')['imagen_url'];?>
" alt="Imagen de la noticia">
            <div class="noticia-content">
                <h2><?php echo $_smarty_tpl->getValue('noticia')['titulo'];?>
</h2>
                <p><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('truncate')($_smarty_tpl->getValue('noticia')['parrafo'],100,"...");?>
</p>
            </div>
        </a>
    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
</div>

<?php $_smarty_tpl->renderSubTemplate('file:footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
