<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Request::macro('isFromFrontend', function(){
      $referer = $this->header('referer');
      $frontendUrl = config('app.frontend_url');

      return $referer && Str::contains($referer, $frontendUrl);
    });

    ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
      return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
    });

    //throw error if n+1
    Model::preventLazyLoading(! app()->isProduction());

    //listen and writing query to log
    if(config('app.debug')) {
      DB::listen(function ($query) {
        Log::channel('query')->info("Time: {$query->time}ms | SQL: {$query->sql} | Bindings: " . json_encode($query->bindings));
      });
    }
  }
}
