<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Enpoints Auth
 */
Route::middleware(['access', 'cors', 'we-want-json'])->group(function () {
    Route::prefix('/auth')->group(
        function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/validate-mail', [AuthController::class, 'validateMail']);
            Route::post('/sign-in', [AuthController::class, 'login']);
        }
    );
});


/**
 * Enpoints Users
 */
Route::middleware(['access', 'cors', 'auth:api'])->group(function () {
    Route::prefix('/user')->group(
        function () {
            Route::get('/all', [UserController::class, 'all']);
            Route::get('/id/{id}', [UserController::class, 'getById']);
            Route::get('/email/{email}', [UserController::class, 'getByEmail']);
            Route::post('/search', [UserController::class, 'search'])->middleware('we-want-json');
        }
    );
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::middleware(['access', 'cors', 'auth:api'])->group(function () {
    Route::prefix('/user')->group(
        function () {
            Route::get('/email/{email}', [UserController::class, 'getByEmail']);
            Route::get('/get-user', [UserController::class, 'getUser']);
        }
    );
});
