<?php

namespace App\Providers;

use App\Repositories\ChatRepository;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            ChatRepositoryInterface::class,
            ChatRepository::class
        );
        $this->app->bind(
            MessageRepositoryInterface::class,
            MessageRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('api', function ($success, $message, $data = null, $statusCode = 200) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ], $statusCode);
        });

    }
}
