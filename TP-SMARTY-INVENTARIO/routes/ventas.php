<?php

// routes/ventas.php
$router->get('/ventas', function () use ($ventaController) {
    $ventaController->index();
}, ['onlysupervisor']);
$router->get('/ventas/add', function () use ($ventaController) {
    $ventaController->showFormCreate();
}, ['onlysupervisor']);
$router->post('/ventas/create', function () use ($ventaController) {
    $ventaController->create();
}, ['onlysupervisor']);
$router->get('/ventas/edit/{id}', function ($id) use ($ventaController) {
    $ventaController->showFormEdit($id);
}, ['onlysupervisor']);
$router->post('/ventas/update', function () use ($ventaController) {
    $ventaController->update();
}, ['onlysupervisor']);
$router->get('/ventas/delete/{id}', function ($id) use ($ventaController) {
    $ventaController->showConfirmDelete($id);
}, ['onlysupervisor']);
$router->post('/ventas/delete_confirm/{id}', function ($id) use ($ventaController) {
    $ventaController->delete($id);
}, ['onlysupervisor']);
$router->get('/ventas/detail/{id}', function ($id) use ($ventaController) {
    $ventaController->showDetail($id);
}, ['onlysupervisor']);