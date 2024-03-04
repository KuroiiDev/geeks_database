<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\GenresController;
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

    $router->get('/book', [BooksController::class,'index']);
    $router->get('/book/{id}', [BooksController::class, 'getByID']);
    //$router->get('/bookmark/{id}', 'KoleksiController@getByUser');
    //$router->post('/bookmark', 'KoleksiController@store');

    //$router->get('/rating/{id}', 'UlasanController@getByUser');
    //$router->post('/rating', 'UlasanController@store');
    
    //$router->get('/rent/{id}', 'PeminjamanController@getByUser');
    //$router->post('/rent', 'PeminjamanController@store');
});
$router->group(['prefix' => 'staff'], function () use ($router) {

     $router->post('/login', [UsersController::class, 'staffLogin']);

     $router->post('/book', [BooksController::class,'store']);
     $router->get('/book/{id}', [BooksController::class, 'getByID']);
     $router->patch('/book/{id}', [BooksController::class, 'update']);
     $router->get('/book', [BooksController::class,'index']);
     $router->get('/bookAZ', [BooksController::class,'AZ']);

     $router->get('/genre', [GenresController::class,'index']);

//     $router->get('/pinjam', 'PeminjamanController@index');
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
