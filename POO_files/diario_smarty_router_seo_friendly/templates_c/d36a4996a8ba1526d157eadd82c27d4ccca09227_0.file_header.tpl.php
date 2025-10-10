<?php
/* Smarty version 5.5.2, created on 2025-10-10 02:42:40
  from 'file:header.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.2',
  'unifunc' => 'content_68e872a0435762_90742177',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd36a4996a8ba1526d157eadd82c27d4ccca09227' => 
    array (
      0 => 'header.tpl',
      1 => 1760062891,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68e872a0435762_90742177 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/jmro/Documentos/TECDA/SEGUNDO/POO/diario_smarty_router_seo_friendly/templates';
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
