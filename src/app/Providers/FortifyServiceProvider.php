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
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Validation\ValidationException;

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

        // ログイン処理のカスタマイズ
        Fortify::authenticateUsing(function (Request $request) {
            // LoginRequestを使用してバリデーション
            $loginRequest = new LoginRequest();

            try {
                $loginRequest->setContainer($this->app)->setRedirector($this->app['redirect']);
                $loginRequest->validateResolved();
            } catch (ValidationException $e) {
                throw $e;
            }

            // バリデーション通過後のログイン処理
            if (Auth::attempt($request->only('email', 'password'))) {
                return Auth::user();
            }

            // 認証失敗時の処理
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        });

        // 会員登録処理のカスタマイズ
        $this->app->singleton(\Laravel\Fortify\Contracts\CreatesNewUsers::class, function ($app) {
            return new class implements \Laravel\Fortify\Contracts\CreatesNewUsers {
                public function create(array $input) {
                    // RegisterRequestを使用してバリデーション
                    $registerRequest = new RegisterRequest();
                    $registerRequest->replace($input);

                    try {
                        app()->call([$registerRequest, 'validateResolved']);
                    } catch (ValidationException $e) {
                        throw $e;
                    }

                    // バリデーション通過後の処理
                    return app(CreateNewUser::class)->create($input);
                }
            };
        });

        // ログアウト後、ログイン画面にリダイレクト
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect()->route('login');
            }
        });
    }
}