<?php

namespace dacoto\LaravelInstaller\Providers;

use dacoto\LaravelInstaller\Middleware\InstallerMiddleware;
use dacoto\LaravelInstaller\Middleware\ToInstallMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LaravelDashboardInstallerProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/installer.php', 'installer');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function boot(Router $router, Kernel $kernel): void
    {
        $kernel->prependMiddlewareToGroup('web', ToInstallMiddleware::class);
        $router->pushMiddlewareToGroup('installer', InstallerMiddleware::class);
        $this->loadViewsFrom(__DIR__.'/../Views', 'installer');
    }
}
