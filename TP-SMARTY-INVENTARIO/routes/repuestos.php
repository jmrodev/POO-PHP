<?php

// routes/repuestos.php
$router->get('/repuestos', function () use ($repuestoController) {
    $repuestoController->index();
}, ['supervisor']);
$router->get('/repuestos/add', function () use ($repuestoController) {
    $repuestoController->showFormCreate();
}, ['supervisor']);
$router->post('/repuestos/create', function () use ($repuestoController) {
    $repuestoController->create();
}, ['supervisor']);
$router->get('/repuestos/edit/{id}', function ($id) use ($repuestoController) {
    $repuestoController->showFormEdit($id);
}, ['supervisor']);
$router->post('/repuestos/update', function () use ($repuestoController) {
    $repuestoController->update();
}, ['supervisor']);
$router->get('/repuestos/delete/{id}', function ($id) use ($repuestoController) {
    $repuestoController->showConfirmDelete($id);
}, ['supervisor']);
$router->post('/repuestos/delete_confirm/{id}', function ($id) use ($repuestoController) {
    $repuestoController->delete($id);
}, ['supervisor']);
$router->get('/repuestos/detail/{id}', function ($id) use ($repuestoController) {
    $repuestoController->showDetail($id);
}, ['user', 'supervisor']);