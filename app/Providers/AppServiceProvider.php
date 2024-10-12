<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('mask', function ($expression) {
            return "<?php echo substr($expression, 0, 3) . str_repeat('*', strlen($expression) - 6) . substr($expression, -3); ?>";
        });
    }
}
