<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [\App\Http\Controllers\PageController::class, 'index']);

Route::get('/search', [\App\Http\Controllers\ActionController::class, 'search']);

Route::middleware('auth:web')->group(function () {

    // 乘客路由组 前缀=>'passenger' ---- /passenger/xxx
    Route::prefix('passenger')->group(function () {
        // 乘客添加功能
        Route::post('/add', [\App\Http\Controllers\PassengerController::class, 'addPassenger']);
        // 乘客删除功能
        Route::get('/del/{id}', [\App\Http\Controllers\PassengerController::class, 'delPassenger']);
        // 乘客退票功能
        Route::get('/cancel/{id}', [\App\Http\Controllers\Passengerontroller::class, 'cancelPassenger']);
        // 乘客改签功能
        Route::get('/change/{order_id}/{op_id}', [\App\Http\Controllers\PassengerController::class, 'changePassenger']);
    });

    // 订单路由组 前缀=>'order' ---- /order/xxx
    Route::prefix('order')->group(function () {
        // 个人订单页面
        Route::get('/', [\App\Http\Controllers\PageController::class, 'myOrder']);
        // 订单确认页面{key} Session 和 trains集合的下标
        Route::get('/confirm/{key}/{seat_class}', [\App\Http\Controllers\PageController::class, 'orderConfirm']);
        // 订单确认功能
        Route::post('/confirm', [\App\Http\Controllers\ActionController::class, 'orderStore']);
        // 订单取消功能
        Route::get('/cancel', [\App\Http\Controllers\PageController::class, 'orderCancel']);
        // 订单结算界面 {id} Order->id
        Route::get('/{id}', [\App\Http\Controllers\PageController::class, 'orderResult']);
    });
});


Auth::routes();

Route::get('/home', function () {
    return redirect('/');
})->name('home');
