<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('index');

// マイリスト（いいねした商品）
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {

    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'show'])->name('show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('update');

    Route::get('/mypage/profile', [ProfileController::class, 'mypageshow'])->name('profile.show');
    Route::post('/mypage/profile', [ProfileController::class, 'mypageupdata'])->name('profile.update');

    // マイページ
    Route::get('/mypage', [ItemController::class, 'mypage'])->name('mypage');
    // マイページ（購入した商品一覧）
    Route::get('/mypage/buy', [ItemController::class, 'mypage'])->name('mypage.buy');
    // マイページ（出品した商品一覧）
    Route::get('/mypage/sell', [ItemController::class, 'mypage'])->name('mypage.sell');

    // 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 商品購入
    Route::get('/purchase/{item}', [ItemController::class, 'purchase'])->name('items.purchase');
    Route::post('/purchase/{item}/payment', [ItemController::class, 'selectPayment'])->name('items.selectPayment');
    Route::get('/purchase/address/{item}', [ItemController::class, 'changeAddress'])->name('items.changeAddress');
    Route::post('/purchase/address/{item}', [ItemController::class, 'AddressUpdate'])->name('items.AddressUpdate');
    Route::post('/purchase/{item}', [ItemController::class, 'completePurchase'])->name('items.completePurchase');

    // いいね機能
    Route::post('/items/{item}/like', [ItemController::class, 'toggleLike'])->name('items.like');

    // コメント機能
    Route::post('/items/{item}/comment', [ItemController::class, 'storeComment'])->name('items.comment');
});

// 商品詳細（認証不要）
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');