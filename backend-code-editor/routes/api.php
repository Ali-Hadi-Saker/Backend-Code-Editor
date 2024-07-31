<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
Route::group([
    "middleware" => "authenticated",
    "prefix" => "messages",
    "controller" => MessageController::class
], function () {
    Route::get('/', 'readAllMessages');
    Route::get('/{id}',  'readMessage');
    Route::post('/', 'createMessage');
    Route::delete('/{id}',  'deleteMessage');
    Route::put('/{id}',  'updateMessage');
    Route::get('/between/{receiver_id}', 'getMessagesByReceiverId');
});
Route::group([
    "middleware" => "authenticated",
    "prefix" => "codes",
    "controller" => CodeController::class
], function () {
    Route::get('/', 'readAllcodes');
    Route::get('/{id}',  'readCode');
    Route::post('/', 'createCode');
    Route::delete('/{id}',  'deleteCode');
    Route::put('/{id}',  'updateCode');
    Route::post('/compile', 'compileCode');
});

Route::group([
    "middleware" => "authenticated",
    "prefix" => "users",
    "controller" => UserController::class
], function () {
    Route::get('/', 'getAllUsers');
});
Route::group([
    "middleware" => ["user.auth", "authenticated"],
    "prefix" => "users",
    "controller" => UserController::class
], function () {
    Route::delete('/{id}', 'deleteUser');
});