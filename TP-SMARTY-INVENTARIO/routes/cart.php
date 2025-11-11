<?php

// routes/cart.php
$router->get('/catalog', function () use ($cartController) {
    $cartController->showCatalog();
}, ['login']);
$router->post('/cart/add', function () use ($cartController) {
    $cartController->addToCart();
}, ['login']);
$router->get('/cart', function () use ($cartController) {
    $cartController->showCart();
}, ['login']);
$router->post('/cart/update', function () use ($cartController) {
    $cartController->updateCartItem();
}, ['login']);
$router->get('/cart/remove/{id}', function ($id) use ($cartController) {
    $cartController->removeFromCart((int)$id);
}, ['login']);
$router->get('/cart/checkout', function () use ($cartController) {
    $cartController->checkout();
}, ['login']);
