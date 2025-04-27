<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        event(new Registered($user));

        // 登録直後にログインさせる！
        Auth::login($user);

        return redirect('/mypage/profile'); // 成功後リダイレクト
    }
}
