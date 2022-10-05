<?php 

require_once __DIR__ . '/loader/bootstrap.php';

$bootstrap = new Bootstrap();
$bootstrap->classes[] = 'class-app.php';
$bootstrap->classes[] = 'class-numeric-field-with-currency-dropdown-list.php';
$bootstrap->register();

$app = new App;
$app->init();
