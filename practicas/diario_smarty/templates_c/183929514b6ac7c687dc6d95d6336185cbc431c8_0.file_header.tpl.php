<?php
/* Smarty version 5.5.2, created on 2025-10-08 11:32:31
  from 'file:header.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.2',
  'unifunc' => 'content_68e64bcf32a5b2_51593352',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '183929514b6ac7c687dc6d95d6336185cbc431c8' => 
    array (
      0 => 'header.tpl',
      1 => 1759923147,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68e64bcf32a5b2_51593352 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/jmro/Documentos/TECDA/SEGUNDO/POO/diario_smarty/templates';
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (($tmp = $_smarty_tpl->getValue('titulo') ?? null)===null||$tmp==='' ? "Diario Smarty" ?? null : $tmp);?>
</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header>
    <h1><?php echo $_smarty_tpl->getValue('titulo');?>
</h1>
</header>
<main>
<?php }
}
