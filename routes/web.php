<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontEndController@index')->name('front');
Route::get('/'.config('settings.url_route').'/{alias}', 'FrontEndController@restorant')->name('vendor');
Route::get('/city/{city}', 'FrontEndController@showStores')->name('show.stores');
Route::get('/lang', 'FrontEndController@langswitch')->name('lang.switch');

Route::post('/search/location', 'FrontEndController@getCurrentLocation')->name('search.location');

Auth::routes(['register' => config('app.isft')]);
/*Route::get( '/auth0/callback', '\Auth0\Login\Auth0Controller@callback' )->name( 'auth0-callback' );
Route::get('/login', 'Auth\Auth0IndexController@login' )->name( 'login' );
Route::get('/logout', 'Auth\Auth0IndexController@logout' )->name( 'logout' )->middleware('auth');
Route::get('/profiles', 'Auth\Auth0IndexController@profile' )->name( 'profiles' )->middleware('auth');
*/

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/paddle', 'PlansController@paddle')->name('paddle');

//Route::group(['middleware' => 'auth'], function () {
Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::post('/user/push', 'UserController@checkPushNotificationId');

    Route::name('admin.')->group(function () {
        Route::get('syncV1UsersToAuth0', 'SettingsController@syncV1UsersToAuth0')->name('syncV1UsersToAuth0');
        Route::get('dontsyncV1UsersToAuth0', 'SettingsController@dontsyncV1UsersToAuth0')->name('dontsyncV1UsersToAuth0');
        Route::resource('restaurants', 'RestorantController');

        Route::get('restaurants/loginas/{restaurant}', 'RestorantController@loginas')->name('restaurants.loginas');

        Route::get('removedemodata', 'RestorantController@removedemo')->name('restaurants.removedemo');
        Route::name('restaurant.')->group(function () {

            //Remove restaurant
            Route::get('removerestaurant/{restaurant}', 'RestorantController@remove')->name('remove');

            // Tables
            Route::get('tables', 'TablesController@index')->name('tables.index');
            Route::get('tables/{table}/edit', 'TablesController@edit')->name('tables.edit');
            Route::get('tables/create', 'TablesController@create')->name('tables.create');
            Route::post('tables', 'TablesController@store')->name('tables.store');
            Route::put('tables/{table}', 'TablesController@update')->name('tables.update');
            Route::get('tables/del/{table}', 'TablesController@destroy')->name('tables.delete');

            // Areas
            Route::resource('restoareas', 'RestoareasController');
            Route::get('restoareas/del/{restoarea}', 'RestoareasController@destroy')->name('restoareas.delete');

            // Areas
            Route::resource('visits', 'VisitsController');
            Route::get('visits/del/{visit}', 'VisitsController@destroy')->name('visits.delete');

            //Coupons
            Route::get('coupons', 'CouponsController@index')->name('coupons.index');
            Route::get('coupons/{coupon}/edit', 'CouponsController@edit')->name('coupons.edit');
            Route::get('coupons/create', 'CouponsController@create')->name('coupons.create');
            Route::post('coupons', 'CouponsController@store')->name('coupons.store');
            Route::put('coupons/{coupon}', 'CouponsController@update')->name('coupons.update');
            Route::get('coupons/del/{coupon}', 'CouponsController@destroy')->name('coupons.delete');

            Route::post('coupons/apply', 'CouponsController@apply')->name('coupons.apply');

            //Banners
            Route::get('banners', 'BannersController@index')->name('banners.index');
            Route::get('banners/{banner}/edit', 'BannersController@edit')->name('banners.edit');
            Route::get('banners/create', 'BannersController@create')->name('banners.create');
            Route::post('banners', 'BannersController@store')->name('banners.store');
            Route::put('banners/{banner}', 'BannersController@update')->name('banners.update');
            Route::get('banners/del/{banner}', 'BannersController@destroy')->name('banners.delete');

            //Language menu
            Route::post('storenewlanguage', 'RestorantController@storeNewLanguage')->name('storenewlanguage');
        });
    });

    Route::resource('cities', 'CitiesController');
    Route::get('/cities/del/{city}', 'CitiesController@destroy')->name('cities.delete');

    Route::post('/updateres/location/{restaurant}', 'RestorantController@updateLocation');
    Route::post('/updateres/radius/{restaurant}', 'RestorantController@updateRadius');
    Route::post('/updateres/delivery/{restaurant}', 'RestorantController@updateDeliveryArea');
    Route::post('/import/restaurants', 'RestorantController@import')->name('import.restaurants');
    Route::get('/restaurant/{restaurant}/activate', 'RestorantController@activateRestaurant')->name('restaurant.activate');
    Route::post('/restaurant/workinghours', 'RestorantController@workingHours')->name('restaurant.workinghours');

    Route::prefix('finances')->name('finances.')->group(function () {
        Route::get('admin', 'FinanceController@adminFinances')->name('admin');
        Route::get('owner', 'FinanceController@ownerFinances')->name('owner');
    });

    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('connect', 'FinanceController@connect')->name('connect');
    });

    Route::resource('reviews', 'ReviewsController');
    Route::get('/reviewsdelete/{rating}', 'ReviewsController@destroy')->name('reviews.destroyget');

    Route::resource('drivers', 'DriverController');
    Route::resource('clients', 'ClientController');
    Route::resource('orders', 'OrderController');
    Route::post('/rating/{order}', 'OrderController@rateOrder')->name('rate.order');
    Route::get('/check/rating/{order}', 'OrderController@checkOrderRating')->name('check.rating');

    Route::get('ordertracingapi/{order}', 'OrderController@orderLocationAPI');
    Route::get('liveapi', 'OrderController@liveapi');
    Route::get('driverlocations', 'DriverController@driverlocations');
    Route::get('restaurantslocations', 'RestorantController@restaurantslocations');

    Route::get('live', 'OrderController@live');
    Route::get('/updatestatus/{alias}/{order}', ['as' => 'update.status', 'uses'=>'OrderController@updateStatus']);

    Route::resource('settings', 'SettingsController');
    Route::get('systemstatus', 'SettingsController@systemstatus')->name('systemstatus');
    Route::get('translatemenu', 'SettingsController@translateMenu')->name('translatemenu');

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::resource('items', 'ItemsController');
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('list/{restorant}', 'ItemsController@indexAdmin')->name('admin');

        // Options
        Route::get('options/{item}', 'Items\OptionsController@index')->name('options.index');
        Route::get('options/{option}/edit', 'Items\OptionsController@edit')->name('options.edit');
        Route::get('options/{item}/create', 'Items\OptionsController@create')->name('options.create');
        Route::post('options/{item}', 'Items\OptionsController@store')->name('options.store');
        Route::put('options/{option}', 'Items\OptionsController@update')->name('options.update');
        Route::get('options/del/{option}', 'Items\OptionsController@destroy')->name('options.delete');

        // Variants
        Route::get('variants/{item}', 'Items\VariantsController@index')->name('variants.index');
        Route::get('variants/{variant}/edit', 'Items\VariantsController@edit')->name('variants.edit');
        Route::get('variants/{item}/create', 'Items\VariantsController@create')->name('variants.create');
        Route::post('variants/{item}', 'Items\VariantsController@store')->name('variants.store');
        Route::put('variants/{variant}', 'Items\VariantsController@update')->name('variants.update');

        Route::get('variants/del/{variant}', 'Items\VariantsController@destroy')->name('variants.delete');
    });

    Route::post('/import/items', 'ItemsController@import')->name('import.items');
    Route::post('/item/change/{item}', 'ItemsController@change');
    Route::post('/{item}/extras', 'ItemsController@storeExtras')->name('extras.store');
    Route::post('/{item}/extras/edit', 'ItemsController@editExtras')->name('extras.edit');
    Route::delete('/{item}/extras/{extras}', 'ItemsController@deleteExtras')->name('extras.destroy');

    Route::resource('categories', 'CategoriesController');

    Route::resource('addresses', 'AddressControler');
    //Route::post('/order/address','AddressControler@orderAddress')->name('order.address');
    Route::get('/new/address/autocomplete', 'AddressControler@newAddressAutocomplete');
    Route::post('/new/address/details', 'AddressControler@newAdressPlaceDetails');
    Route::post('/address/delivery', 'AddressControler@AddressInDeliveryArea');

    Route::post('/change/{page}', 'PagesController@change')->name('changes');

    Route::post('ckeditor/image_upload', 'CKEditorController@upload')->name('upload');
    Route::get('/payment', 'PaymentController@view')->name('payment.view');
    Route::post('/make/payment', 'PaymentController@payment')->name('make.payment');

    if (config('app.isft')) {
        Route::get('/cart-checkout', 'CartController@cart')->name('cart.checkout');
    }

    Route::resource('plans', 'PlansController');
    Route::get('/plan', 'PlansController@current')->name('plans.current');
    Route::post('/subscribe/plan', 'PlansController@subscribe')->name('plans.subscribe');
    Route::post('/subscribe/update', 'PlansController@adminupdate')->name('update.plan');

    Route::get('qr', 'QRController@index')->name('qr');

    Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
    Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');

    Route::get('/execute-payment-pp', 'PaymentController@executePaymentPayPal')->name('execute-payment');

    Route::post('/paypal/subscribe', 'PlansController@subscribePayPal')->name('subscribe-paypal');
    Route::post('subscription/actions', 'PlansController@updateCancelSubscription')->name('subscription.actions');

    Route::get('/share/menu', 'RestorantController@shareMenu')->name('share.menu');
    Route::get('/downloadqr', 'RestorantController@downloadQR')->name('download.menu');
});

if (config('app.isqrsaas')) {
    Route::get('/cart-checkout', 'CartController@cart')->name('cart.checkout');
    Route::get('/guest-orders', 'OrderController@guestOrders')->name('guest.orders');
    Route::post('/whatsapp/store', 'OrderController@storeWhatsappOrder')->name('whatsapp.store');
}

Route::get('/get/rlocation/{restaurant}', 'RestorantController@getLocation');
Route::get('/footer-pages', 'PagesController@getPages');
Route::get('/cart-getContent', 'CartController@getContent')->name('cart.getContent');
Route::post('/cart-add', 'CartController@add')->name('cart.add');
Route::post('/cart-remove', 'CartController@remove')->name('cart.remove');
Route::get('/cart-update', 'CartController@update')->name('cart.update');
Route::get('/cartinc/{item}', 'CartController@increase')->name('cart.increase');
Route::get('/cartdec/{item}', 'CartController@decrease')->name('cart.decrease');

Route::post('/order', 'OrderController@store')->name('order.store');

Route::resource('pages', 'PagesController');
Route::get('/blog/{slug}', 'PagesController@blog')->name('blog');

Route::get('/login/google', 'Auth\LoginController@googleRedirectToProvider')->name('google.login');
Route::get('/login/google/redirect', 'Auth\LoginController@googleHandleProviderCallback');

Route::get('/login/facebook', 'Auth\LoginController@facebookRedirectToProvider')->name('facebook.login');
Route::get('/login/facebook/redirect', 'Auth\LoginController@facebookHandleProviderCallback');

Route::get('/new/restaurant/register', 'RestorantController@showRegisterRestaurant')->name('newrestaurant.register');
Route::post('/new/restaurant/register/store', 'RestorantController@storeRegisterRestaurant')->name('newrestaurant.store');

Route::get('phone/verify', 'PhoneVerificationController@show')->name('phoneverification.notice');
Route::post('phone/verify', 'PhoneVerificationController@verify')->name('phoneverification.verify');

Route::get('/get/rlocation/{restorant}', 'RestorantController@getLocation');
Route::get('/items/variants/{variant}/extras', 'Items\VariantsController@extras')->name('items.variants.extras');

//Languages routes
$availableLanguagesENV = ENV('FRONT_LANGUAGES', 'EN,English,IT,Italian,FR,French,DE,German,ES,Spanish,RU,Russian,PT,Portuguese,TR,Turkish');
$exploded = explode(',', $availableLanguagesENV);
if (count($exploded) > 3 && config('app.isqrsaas')) {
    for ($i = 0; $i < count($exploded); $i += 2) {
        Route::get('/'.strtolower($exploded[$i]), 'FrontEndController@qrsaasMode')->name('lang.'.strtolower($exploded[$i]));
    }
}

Route::get('register/visit/{restaurant_id}', 'VisitsController@register')->name('register.visit');
Route::post('register/visit', 'VisitsController@registerstore')->name('register.visit.store');

//Call Waiter
Route::post('call/waiter/', 'RestorantController@callWaiter')->name('call.waiter');

//Register driver
Route::get('new/driver/register', 'DriverController@register')->name('driver.register');
Route::post('new/driver/register/store', 'DriverController@registerStore')->name('driver.register.store');

Route::post('webhooks/mollie', 'PaymentController@handleWebhookNotification')->name('webhooks.mollie');

Route::get('order/success', 'OrderController@success')->name('order.success');

Route::post('/fb-order', 'OrderController@fbOrderMsg')->name('fb.order');
