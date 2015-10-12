<?php

use Skull\Routing\Route;

Route::get('/', 'IndexController#home');

Route::get("/lol/{name}/post/{age}", function($name, $age) {
    return $name . " est un idiot qui a " . $age . " ans !";
});


Route::post('/user', 'UserController#store');
Route::get('/users', 'UserController#index');
Route::get('/user', 'UserController#create');
Route::get('/users/{id}', 'UserController#show');

Route::otherwise(function() {
    header('HTTP/1.0 404 Not Found');
    exit;
});
