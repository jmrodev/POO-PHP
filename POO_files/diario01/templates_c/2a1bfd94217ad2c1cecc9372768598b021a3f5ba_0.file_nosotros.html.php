<?php
/* Smarty version 5.4.2, created on 2025-10-06 22:30:21
  from 'file:nosotros.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_68e442fdda2eb1_74946440',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2a1bfd94217ad2c1cecc9372768598b021a3f5ba' => 
    array (
      0 => 'nosotros.html',
      1 => 1759789818,
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
function content_68e442fdda2eb1_74946440 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/var/www/html/2025/diario01/templates';
$_smarty_tpl->renderSubTemplate("file:header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
$_smarty_tpl->renderSubTemplate("file:menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

<main class="container mt-5">
    <section class="noticias">
      <ul>
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('nosotros'), 'persona');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('persona')->value) {
$foreach0DoElse = false;
?>
        <li><?php echo $_smarty_tpl->getValue('persona');?>
</li>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
      </ul>
    </section>          
  </main>


<?php $_smarty_tpl->renderSubTemplate("file:footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
