<?php

use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\RatingsController;
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
    $router->get('/id/{id}', [UsersController::class, 'userId']);
    $router->post('/edit/{id}', [UsersController::class, 'update']);

    $router->group(['prefix' => 'book'], function () use ($router) {
        $router->get('/', [BooksController::class,'index']);
        $router->get('/id/{id}', [BooksController::class, 'byID']);
        $router->get('/az', [BooksController::class,'orderAtoZ']);
        $router->get('/top', [BooksController::class,'topRent']);
        $router->get('/rating', [BooksController::class,'topRated']);
    });

    $router->group(['prefix' => 'rent'], function () use ($router) {
        $router->post('/request', [RentsController::class,'requestRent']);

        $router->get('/id/{id}', [RentsController::class,'byId']);
        $router->get('/user/{id}', [RentsController::class,'byUser']);
        $router->get('/current/{id}', [RentsController::class,'current']);
    });

    $router->group(['prefix' => 'bookmark'], function () use ($router) {
        $router->get('/user/{id}', [BookmarksController::class,'index']);
        $router->post('/add', [BookmarksController::class,'store']);
        $router->post('/check', [BookmarksController::class,'check']);
        $router->get('/remove/{id}', [BookmarksController::class,'destroy']);
    });

    $router->group(['prefix' => 'rating'], function () use ($router) {
        $router->get('/', [RatingsController::class,'index']);
        $router->post('/add', [RatingsController::class,'store']);
        $router->get('/book/{id}', [RatingsController::class,'book']);
    });

    $router->get('/genre', [GenresController::class,'index']);
 
});
$router->group(['prefix' => 'staff'], function () use ($router) {

    $router->post('/login', [UsersController::class, 'staffLogin']);
    $router->post('/register', [UsersController::class, 'adminRegister']);
    $router->post('/registerStaff', [UsersController::class, 'staffRegister']);
    $router->get('/users', [UsersController::class, 'indexUser']);
    $router->get('/staffs', [UsersController::class, 'indexStaff']);

    $router->group(['prefix' => 'book'], function () use ($router) {
        $router->post('/', [BooksController::class,'store']);
        $router->get('/id/{id}', [BooksController::class, 'getByID']);
        $router->patch('/id/{id}', [BooksController::class, 'update']);
        $router->get('/', [BooksController::class,'index']);
    });

    $router->group(['prefix' => 'genre'], function () use ($router) {
        $router->get('/', [GenresController::class,'index']);
        $router->post('/', [GenresController::class,'store']);
    });
     

     $router->group(['prefix' => 'rent'], function () use ($router) {
        $router->get('/', [RentsController::class,'index']);
        $router->get('/verify/{id}', [RentsController::class,'verifyRent']);
        $router->get('/return/{id}', [RentsController::class,'returnRent']);
     });
});
