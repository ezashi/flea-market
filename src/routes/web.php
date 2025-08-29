<?php

use App\Http\Middleware\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {

    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'show'])->name('show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('update');

    Route::get('/mypage/profile', [ProfileController::class, 'mypageshow'])->name('profile.show');
    Route::post('/mypage/profile', [ProfileController::class, 'mypageupdata'])->name('profile.update');

    // マイページ
    Route::get('/mypage', [ItemController::class, 'mypage'])->name('mypage');

    // 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 商品購入
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('items.purchase');
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'changeAddress'])->name('items.changeAddress');
    Route::post('/purchase/address/{item_id}', [ItemController::class, 'AddressUpdate'])->name('items.AddressUpdate');
    Route::post('/purchase/{item_id}', [ItemController::class, 'completePurchase'])->name('items.completePurchase');

    // いいね機能
    Route::post('/items/{item_id}/like', [ItemController::class, 'toggleLike'])->name('items.like');

    // コメント機能
    Route::post('/items/{item_id}/comment', [ItemController::class, 'storeComment'])->name('items.comment');

    // チャット機能
    Route::get('/chat/{item_id}', [ChatController::class, 'show'])->name('chat.show')->middleware('chat.message');
    Route::post('/chat/{item_id}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/{item_id}/draft', [ChatController::class, 'saveDraft'])->name('chat.saveDraft');
    Route::get('/chat/message/{message_id}/edit', [ChatController::class, 'edit'])->name('chat.edit');
    Route::post('/chat/message/{message_id}/edit', [ChatController::class, 'update'])->name('chat.update');
    Route::post('/chat/message/{message_id}/delete', [ChatController::class, 'delete'])->name('chat.delete');

    // 取引完了
    Route::post('/items/{item_id}/complete', [ItemController::class, 'completeTransaction'])->name('items.completeTransaction');

    // 評価機能
    Route::post('/evaluation/{item_id}', [EvaluationController::class, 'store'])->name('evaluation.store');
});

// 商品詳細（認証不要）
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');