<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TodoController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
Route::group([
    "prefix" => "messages",
    "controller" => MessageController::class
], function () {
    Route::get('/', 'readAllMessages');
    Route::get('/{id}',  'readMessage');
    Route::post('/', 'createMessage');
    Route::delete('/{id}',  'deleteMessage');
    Route::put('/{id}',  'updateMessage');
});
Route::group([
    "prefix" => "codes",
    "controller" => CodeController::class
], function () {
    Route::get('/', 'readAllcodes');
    Route::get('/{id}',  'readCode');
    Route::post('/', 'createCode');
    Route::delete('/{id}',  'deleteCode');
    Route::put('/{id}',  'updateCode');
});