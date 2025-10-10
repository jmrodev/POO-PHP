<?php
/* Smarty version 5.4.2, created on 2025-10-06 22:23:28
  from 'file:index.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_68e4416031c748_73047265',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '73a6505d7ce9d524fc5e881f271caa72c0d28ee4' => 
    array (
      0 => 'index.html',
      1 => 1759789337,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.html' => 1,
    'file:menu.html' => 1,
    'file:footer.html' => 1,
  ),
))) {
function content_68e4416031c748_73047265 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/var/www/html/2025/diario01/templates';
$_smarty_tpl->renderSubTemplate("file:header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

<main class="container mt-5">
    <section class="noticias">
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('noticias'), 'noticia');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('noticia')->value) {
$foreach0DoElse = false;
?> 
              <div class=\"card\">
              <img src="<?php echo $_smarty_tpl->getValue('noticia')['img'];?>
" class="card-img-top" alt="...">
              
              <div class="card-body">
              <h5 class="card-title"><?php echo $_smarty_tpl->getValue('noticia')['title'];?>
</h5>
              <p class="card-text"><?php echo $_smarty_tpl->getValue('noticia')['text'];?>
</p>
              <a href="noticia.php?id=<?php echo $_smarty_tpl->getValue('noticia')['id'];?>
" class="btn btn-outline-primary">Leer más</a>
              </div>
              
              </div>
           <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
      </section>
      
  </main>


<?php $_smarty_tpl->renderSubTemplate("file:footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

<?php }
}
