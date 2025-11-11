<?php

// routes/usuarios.php
$router->get('/usuarios', function () use ($usuarioController) {
    $usuarioController->index();
}, ['admin']);
$router->get('/usuarios/add', function () use ($usuarioController) {
    $usuarioController->showFormCreate();
}, ['admin']);
$router->post('/usuarios/create', function () use ($usuarioController) {
    $usuarioController->create();
}, ['admin']);
$router->get('/usuarios/edit/{id}', function ($id) use ($usuarioController) {
    $usuarioController->showFormEdit($id);
}, ['admin']);
$router->post('/usuarios/update', function () use ($usuarioController) {
    $usuarioController->update();
}, ['admin']);
$router->get('/usuarios/delete/{id}', function ($id) use ($usuarioController) {
    $usuarioController->showConfirmDelete($id);
}, ['admin']);
$router->post('/usuarios/delete_confirm/{id}', function ($id) use ($usuarioController) {
    $usuarioController->delete($id);
}, ['admin']);