<?php

use Facades\Route;
use Facades\View;

Route::get('/', function() {
    return View::make('home');
});

Route::otherwise(function() {
    header('HTTP/1.0 404 Not Found');
    exit;
});
