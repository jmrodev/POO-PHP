<?php
/* Smarty version 5.4.2, created on 2025-10-06 22:16:02
  from 'file:prueba.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_68e43fa236ca62_16297447',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'db669981d9236d3ed40a2ea9eea4f8018b207802' => 
    array (
      0 => 'prueba.tpl',
      1 => 1759788956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68e43fa236ca62_16297447 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/var/www/html/2025/diario01/templates';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $_smarty_tpl->getValue('titulo');?>
</title>

  <!-- Bootstrap CSS -->
<link 
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
  rel="stylesheet"
  integrity="sha384-Gnb38rZCEiS8KjV+H+0+CFYwLg2T8FoylF47CIhtjHekH1rCh6KltK+M6X9F+n3N" 
  crossorigin="anonymous">

</head>
<body>

  <!-- Navbar de ejemplo -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><?php echo $_smarty_tpl->getValue('titulo');?>
</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('secciones')) == 0) {?>
            <h3>No hay items</h3>
        <?php } else { ?>
        <ul class="navbar-nav ms-auto">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('secciones'), 'item', false, 'key', 'name', array (
));
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
                <li class="nav-item">
                    <a class="nav-link active" href="#"><?php echo $_smarty_tpl->getValue('item');?>
</a>
                </li>                
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </ul>
        <?php }?>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <div class="container mt-5">
    <h1>Bienvenido a mi página</h1>
    <p class="lead">Esta es una plantilla básica usando Bootstrap 5.</p>
  </div>

  <!-- Bootstrap JS (con Popper) -->
<?php echo '<script'; ?>
 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-6Bpi7TnjVHXsIq5Az9zEppEJQYk/HEJeVZp6oU96TIkD9zX9eK+7gx3jVPZ5W7CM" 
  crossorigin="anonymous">
<?php echo '</script'; ?>
>

</body>
</html>
<?php }
}
