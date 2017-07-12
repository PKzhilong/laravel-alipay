<?php

namespace Laravel\AliPay;

use Illuminate\Support\ServiceProvider;

class AliPayServeProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . 'config/pkxing-alipay.php' => config('pkxing-alipay.php')
        ]);
    }

    /*
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('alipay', function(){
            return new AliPay();
        });
        $this->mergeConfigFrom(
            __DIR__ . '/config/pkxing-alipay.php', 'pkxing-alipay'
        );
    }

    public function provides()
    {
        return [AliPay::class];
    }
}
