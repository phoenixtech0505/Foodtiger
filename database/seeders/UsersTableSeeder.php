<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => config('settings.admin_name'),
            'email' =>  config('settings.admin_email'),
            'password' => Hash::make(config('settings.admin_password')),
            'api_token' => Str::random(80),
            'email_verified_at' => now(),
            'phone' =>  '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Settings
        DB::table('settings')->insert([
            'site_name' => config('app.name'),
            'site_logo' =>  config('app.isqrsaas') ? '/default/logo_qrzebra.png' : '/default/logo.png',
            'site_logo_dark' => config('app.isqrsaas') ? '/default/logo_qrzebra_white.png' : null,
            'restorant_details_image' => '/default/restaurant_large.jpg',
            'restorant_details_cover_image' => '/default/cover.jpg',
            'search' => '/default/cover.jpg',
            'description' => 'Food Delivery from best restaurants',
            'header_title' => 'Food Delivery from<br /><b>New York’s</b> Best Restaurants',
            'header_subtitle' => 'The meals you love, delivered with care',
            'created_at' => now(),
            'updated_at' => now(),
            'delivery'=> 0,
            'maps_api_key' => 'AIzaSyCZhq0g1x1ttXPa1QB3ylcDQPTAzp_KUgA',
            'mobile_info_title' => 'Download the food you love',
            'mobile_info_subtitle' => 'It`s all at your fingertips - the restaurants you love. Find the right food to suit your mood, and make the first bite last. Go ahead, download us.',
        ]);
    }
}
