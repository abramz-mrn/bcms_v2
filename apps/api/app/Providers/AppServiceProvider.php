<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Billing\InvoiceGenerator;
use App\Services\Notifications\Notifier;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Network\MikrotikClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InvoiceGenerator::class);
        $this->app->singleton(Notifier::class);
        $this->app->singleton(PaymentGatewayManager::class);
        $this->app->singleton(MikrotikClient::class);
    }

    public function boot(): void
    {
        //
    }
}
