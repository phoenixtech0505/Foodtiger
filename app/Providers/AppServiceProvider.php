<?php

namespace App\Providers;

use App\Settings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //ignore default migrations from Cashier
        Cashier::ignoreMigrations();

        /* $this->app->bind(
             \Auth0\Login\Contract\Auth0UserRepository::class,
             \App\Repositories\CustomUserRepository::class
         );*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::bootstrap-4');
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
        try {
            \DB::connection()->getPdo();
            $settings = Schema::hasTable('settings') && Settings::find(1) ? Settings::find(1)->toArray() : [];

            //Site logo
            if ((isset($settings['site_logo']) && ! (strpos($settings['site_logo'], '/') !== false))) {
                $settings['site_logo'] = '/uploads/settings/'.$settings['site_logo'].'_logo.jpg';
            }

            //Site logo dark
            if ((isset($settings['site_logo_dark']) && ! (strpos($settings['site_logo_dark'], '/') !== false))) {
                $settings['site_logo_dark'] = '/uploads/settings/'.$settings['site_logo_dark'].'_site_logo_dark.jpg';
            }

            //Search
            if ((isset($settings['search']) && ! (strpos($settings['search'], '/') !== false))) {
                $settings['search'] = '/uploads/settings/'.$settings['search'].'_cover.jpg';
            }

            //Details default cover image
            if ((isset($settings['restorant_details_cover_image']) && ! (strpos($settings['restorant_details_cover_image'], '/') !== false))) {
                $settings['restorant_details_cover_image'] = '/uploads/settings/'.$settings['restorant_details_cover_image'].'_cover.jpg';
            }

            //Restaurant default image
            if ((isset($settings['restorant_details_image']) && ! (strpos($settings['restorant_details_image'], '/') !== false))) {
                $settings['restorant_details_image'] = '/uploads/settings/'.$settings['restorant_details_image'].'_large.jpg';
            }

            config([
                'global' =>  $settings,
            ]);
        } catch (\Exception $e) {
            //return redirect()->route('LaravelInstaller::welcome');
        }
    }
}
