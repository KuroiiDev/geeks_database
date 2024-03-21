<?php

use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\RentsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return response()->json(['status' => 'working!']);
});

$router->group(['prefix' => 'user'], function () use ($router) {

    $router->post('/login', [UsersController::class, 'login']);
    $router->post('/register', [UsersController::class, 'register']);

    $router->group(['prefix' => 'book'], function () use ($router) {
        $router->get('/', [BooksController::class,'index']);
        $router->get('/id/{id}', [BooksController::class, 'byID']);
        $router->get('/az', [BooksController::class,'orderAtoZ']);
        $router->get('/top', [BooksController::class,'topRent']);
    });

    $router->group(['prefix' => 'rent'], function () use ($router) {
        $router->post('/request', [RentsController::class,'requestRent']);

        $router->get('/id/{id}', [RentsController::class,'byId']);
        $router->get('/return/{id}', [RentsController::class,'returnRent']);
    });

    $router->group(['prefix' => 'bookmark'], function () use ($router) {
        $router->get('/user/{id}', [BookmarksController::class,'index']);
        $router->post('/add', [BookmarksController::class,'store']);
        $router->get('/remove/{id}', [BookmarksController::class,'store']);
    });

});
$router->group(['prefix' => 'staff'], function () use ($router) {

     $router->post('/login', [UsersController::class, 'staffLogin']);

     $router->post('/book', [BooksController::class,'store']);
     $router->get('/book/id/{id}', [BooksController::class, 'getByID']);
     $router->patch('/book/id/{id}', [BooksController::class, 'update']);
     $router->get('/book', [BooksController::class,'index']);

     $router->group(['prefix' => 'rent'], function () use ($router) {
        $router->get('/', [RentsController::class,'index']);
        $router->get('/verify/{id}', [RentsController::class,'verifyRent']);
     });
     

     $router->get('/genre', [GenresController::class,'index']);
});

$router->group(['prefix' => 'admin'], function () use ($router) {

    $router->post('/login', [UsersController::class, 'staffLogin']);
    $router->post('/register', [UsersController::class, 'adminRegister']);
    $router->post('/registerStaff', [UsersController::class, 'staffRegister']);
    $router->get('/users', [UsersController::class, 'indexUser']);
    $router->get('/staffs', [UsersController::class, 'indexStaff']);

    $router->post('/book', [BooksController::class,'store']);

    $router->get('/book', [BooksController::class,'index']);

    $router->get('/genre', [GenresController::class,'index']);
     $router->post('/genre', [GenresController::class,'store']);
});
