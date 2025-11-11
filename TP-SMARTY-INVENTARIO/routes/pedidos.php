<?php

// routes/pedidos.php
$router->get('/pedidos', function () use ($pedidoController) {
    $pedidoController->index();
}, ['login']);
$router->get('/pedidos/detail/{id}', function ($id) use ($pedidoController) {
    $pedidoController->showDetail((int)$id);
}, ['login']);
$router->get('/pedidos/edit/{id}', function ($id) use ($pedidoController) {
    $pedidoController->showFormEdit((int)$id);
}, ['login']);
$router->post('/pedidos/update', function () use ($pedidoController) {
    $pedidoController->update();
}, ['login']);
$router->get('/pedidos/delete/{id}', function ($id) use ($pedidoController) {
    $pedidoController->showConfirmDelete((int)$id);
}, ['login']);
$router->post('/pedidos/delete_confirm/{id}', function ($id) use ($pedidoController) {
    $pedidoController->delete((int)$id);
}, ['login']);
