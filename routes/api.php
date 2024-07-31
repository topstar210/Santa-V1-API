<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\MailTestController;

use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-by-code', [AuthController::class, 'loginByCode']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerify'])->name('verification.verify');

    Route::group(['middleware' => 'auth:api'], function () {
        # Auth Routes
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('profile', [AuthController::class, 'profile']);

        # User Routes
        Route::resource('users', UserController::class);
        Route::get('user/analytic', [UserController::class, 'analytic']);
        Route::post('user/add', [UserController::class, 'create']);
        Route::post('user/add-tempuser', [UserController::class, 'createTempUser']);
        Route::post('user/delete-user/{id}', [UserController::class, 'delete']);
        Route::post('user/delete-temp-user/{id}', [UserController::class, 'delete']);

        # Module Routes
        Route::resource('modules', ModuleController::class);
        Route::get('module/analytic', [ModuleController::class, 'analytic']);
        Route::post('module/create', [ModuleController::class, 'store']);

        # Product Routes
        Route::resource('products', ProductController::class);
    });

    Route::get('/send-test-email', [MailTestController::class, 'sendTestEmail']);
});
