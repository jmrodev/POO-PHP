<?php

// routes/ventas.php
$router->get('/ventas', function () use ($ventaController) {
    $ventaController->index();
}, ['supervisor']);
$router->get('/ventas/add', function () use ($ventaController) {
    $ventaController->showFormCreate();
}, ['supervisor']);
$router->post('/ventas/create', function () use ($ventaController) {
    $ventaController->create();
}, ['supervisor']);
$router->get('/ventas/edit/{id}', function ($id) use ($ventaController) {
    $ventaController->showFormEdit($id);
}, ['supervisor']);
$router->post('/ventas/update', function () use ($ventaController) {
    $ventaController->update();
}, ['supervisor']);
$router->get('/ventas/delete/{id}', function ($id) use ($ventaController) {
    $ventaController->showConfirmDelete($id);
}, ['supervisor']);
$router->post('/ventas/delete_confirm/{id}', function ($id) use ($ventaController) {
    $ventaController->delete($id);
}, ['supervisor']);
$router->get('/ventas/detail/{id}', function ($id) use ($ventaController) {
    $ventaController->showDetail($id);
}, ['supervisor']);