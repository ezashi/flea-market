<?php

namespace App\Providers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use App\Responses\RegisterResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Responses\RegisterResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::authenticateUsing(function (Request $request) {
            // LoginRequestを使用してバリデーション
            $loginRequest = new LoginRequest();
            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $loginRequest->rules(),
                $loginRequest->messages()
            );

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // 認証処理
            if (Auth::attempt($request->only('email', 'password'))) {
                return Auth::user();
            }

            // 認証失敗時のエラーメッセージ
            return back()->withErrors([
                'email' => [trans('auth.failed')],
            ])->withInput();
        });

        // カスタム登録バリデーション
        Fortify::registerView(function () {
            return view('auth.register');
        });

        $this->app->singleton(\Laravel\Fortify\Contracts\CreatesNewUsers::class, function ($app) {
            return new class implements \Laravel\Fortify\Contracts\CreatesNewUsers {
                public function create(array $input){
                    $request = new Request($input);
                    $registerRequest = new RegisterRequest();

                    $validator = \Illuminate\Support\Facades\Validator::make(
                        $request->all(),
                        $registerRequest->rules(),
                        $registerRequest->messages()
                    );

                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }

                    return app(CreateNewUser::class)->create($input);
                }
            };
        });

        //ログアウト後、ログイン画面にリダイレクト
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect()->route('login');
            }
        });
    }
}