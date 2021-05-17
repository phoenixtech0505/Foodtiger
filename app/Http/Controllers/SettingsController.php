<?php

namespace App\Http\Controllers;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Address;
use App\Categories;
use App\Extras;
use App\Items;
use App\Models\LocalMenu;
use App\Models\Options;
use App\Notifications\SystemTest;
use App\Restorant;
use App\Settings;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected static $currencies;
    protected static $jsfront;
    protected $imagePath = '/uploads/settings/';

    public function systemstatus()
    {
        $totalTasks = 3;
        $percent = 100 / $totalTasks;
        $taskDone = 0;

        //Verify system is setup correctly.
        if (! auth()->user()->hasRole('admin')) {
            abort(404);
        }

        $testResutls = [];
        //1. Make sure admin email is not admin@example.com
        if (auth()->user()->email !== 'admin@example.com') {
            array_push($testResutls, ['settings.default_admin_email', 'OK', true]);
            $taskDone++;

            //Continue to verify smtp setup
            if (config('mail.mailers.smtp.username') != '802fc656dd8029') {
                try {
                    auth()->user()->notify(new SystemTest(auth()->user()));
                    array_push($testResutls, ['settings.smtp', 'OK', true]);
                    $taskDone++;

                    //Now in qr, we need paddle vendor id or stripe s
                    if (config('settings.subscription_processor') == 'Paddle') {
                        //Check paddle
                        if (config('settings.paddlevendorid') && strlen(config('settings.paddlevendorid') > 3)) {
                            array_push($testResutls, ['settings.paddle', 'OK', true]);
                            $taskDone++;
                        } else {
                            array_push($testResutls, ['settings.paddle', 'settings.paddle_error', false, 'https://mobidonia.gitbook.io/qr-menu-maker/define-basics/payments']);
                        }
                    } else {
                        //Check stripe
                        if (config('settings.stripe_key') && strlen(config('settings.stripe_key')) > 3 && config('settings.stripe_key') != 'pk_test_XXXXXXXXXXXXXX' && config('settings.stripe_secret') != 'sk_test_XXXXXXXXXXXXXXX') {
                            array_push($testResutls, ['settings.stripe', 'OK', true]);
                            $taskDone++;
                        } else {
                            array_push($testResutls, ['settings.stripe', 'settings.stripe_error', false, 'https://mobidonia.gitbook.io/qr-menu-maker/define-basics/payments']);
                        }
                    }
                } catch (\Exception $e) {
                    array_push($testResutls, ['settings.smtp', 'settings.smtp_not_ok', false, 'https://mobidonia.gitbook.io/qr-menu-maker/define-basics/obtain-smtp']);
                }
            } else {
                array_push($testResutls, ['settings.smtp', 'settings.smtp_not_ok', false, 'https://mobidonia.gitbook.io/qr-menu-maker/define-basics/obtain-smtp']);
            }
        } else {
            array_push($testResutls, ['settings.default_admin_email', 'settings.using_default_admin_solution', false, 'https://mobidonia.gitbook.io/qr-menu-maker/usage/getting-started#login-as-admin']);
        }

        return view('settings.status', [
            'progress'=>ceil($taskDone * $percent),
            'testResutls' => $testResutls, ]);
    }

    private function translateModel($tableName, $provider, $fields, $locale)
    {
        $items = DB::table($tableName)->get();

        foreach ($items as $key => $item) {
            $object = $provider::find($item->id);
            foreach ($fields as $keyFields => $valueField) {
                $object->setTranslation($valueField, $locale, $item->name)->save();
            }
        }
    }

    public function translateMenu()
    {
        if (auth()->user()->hasRole('admin')) {
            $locale = config('settings.app_locale');

            //Translate categories
            $this->translateModel('categories', Categories::class, ['name'], $locale);

            //Translate items
            $this->translateModel('items', Items::class, ['name', 'description'], $locale);

            //Translate extras
            $this->translateModel('extras', Extras::class, ['name'], $locale);

            //Translate Options
            $this->translateModel('options', Options::class, ['name'], $locale);

            //Create the local model for all restaurants
            $allRestaurants = Restorant::where('id', '>', 0)->get();
            $currentEnvLanguage = isset(config('config.env')[2]['fields'][0]['data'][$locale]) ? config('config.env')[2]['fields'][0]['data'][$locale] : 'UNKNOWN';
            foreach ($allRestaurants as $key => $restaurant) {
                $localMenu = new LocalMenu([
                    'restaurant_id'=>$restaurant->id,
                     'language'=>$locale,
                      'languageName'=>$currentEnvLanguage,
                      'default'=>'1', ]
                );
                $localMenu->save();
            }

            //Set that we have done the translation
            $data = json_encode([
                'date' => date('Y/m/d h:i:s'),
            ], JSON_THROW_ON_ERROR);
            file_put_contents(storage_path('multilanguagemigrated'), $data, FILE_APPEND | LOCK_EX);

            //Redirect
            return redirect()->route('settings.index')->withStatus(__('Successfully migrated to multi language menus'));
        }
    }

    public function getCurrentEnv()
    {
        $envConfigs = config('config.env');
        $envMerged = [];
        foreach ($envConfigs as $key => $group) {
            $theMegedGroupFields = [];
            foreach ($group['fields'] as $key => $field) {
                if (! (isset($field['onlyin']) && $field['onlyin'] != config('settings.app_project_type'))) {
                    array_push($theMegedGroupFields, [
                        'ftype'=>isset($field['ftype']) ? $field['ftype'] : 'input',
                        'type'=>isset($field['type']) ? $field['type'] : 'text',
                        'id'=>'env['.$field['key'].']',
                        'name'=>isset($field['title']) && $field['title'] != '' ? $field['title'] : $field['key'],
                        'placeholder'=>isset($field['placeholder']) ? $field['placeholder'] : '',
                        'value'=>env($field['key'], $field['value']),
                        'required'=>false,
                        'separator'=>isset($field['separator']) ? $field['separator'] : null,
                        'additionalInfo'=>isset($field['help']) ? $field['help'] : null,
                        'data'=>isset($field['data']) ? $field['data'] : [],
                     ]);
                }
            }
            array_push($envMerged, [
             'name'=>$group['name'],
             'slug'=>$group['slug'],
             'icon'=>$group['icon'],
             'fields'=>$theMegedGroupFields,
            ]);
        }

        return $envMerged;
    }

    public function index(Settings $settings)
    {
        if (auth()->user()->hasRole('admin')) {


            //Always run migration
            $exitCodeForMigration=Artisan::call('migrate', [
                '--force' => true
             ]);

            $updater = new \Codedge\Updater\UpdaterManager(app());

             //With update
             if(isset($_GET['do_update'])){
                if($updater->source()->isNewVersionAvailable()) {

            
                    // Get the new version available
                    $versionAvailable = $updater->source()->getVersionAvailable();
            
                    // Create a release
                    $release = $updater->source()->fetch($versionAvailable);
            
                    // Run the update process
                    $updater->source()->update($release);

                    return redirect()->route('settings.index')->withStatus(__('Successfully updated to version v'.$versionAvailable));
                    
                } else {
                    return redirect()->route('settings.index')->withStatus(__('There is nothing to update!'));
                }
            }
            

            //Check for new version
            $updater->source()->deleteVersionFile();
            $newVersion="";
            $newVersionAvailable = $updater->source()->isNewVersionAvailable();
            if($newVersionAvailable){
                $newVersion=$updater->source()->getVersionAvailable();
            }

            $curreciesArr = [];
            static::$currencies = require __DIR__.'/../../../config/money.php';

            foreach (static::$currencies as $key => $value) {
                array_push($curreciesArr, $key);
            }

            $jsfront = File::get(base_path('public/byadmin/front.js'));
            $jsback = File::get(base_path('public/byadmin/back.js'));
            $cssfront = File::get(base_path('public/byadmin/front.css'));
            $cssback = File::get(base_path('public/byadmin/back.css'));

            //$jsfront = file_get_contents(__DIR__.'/../../../public/byadmin/front.js');
            //dd($cssfront);

            $hasDemoRestaurants = Restorant::where('phone', '(530) 625-9694')->count() > 0;

            if (config('settings.is_demo') | config('settings.is_demo')) {
                $hasDemoRestaurants = false;
            }

            return view('settings.index', [
                'settings' => $settings->first(),
                'currencies' => $curreciesArr,
                'jsfront'=>$jsfront,
                'jsback'=>$jsback,
                'cssfront'=>$cssfront,
                'cssback'=>$cssback,
                'newVersionAvailable'=>$newVersionAvailable,
                'newVersion'=>$newVersion,
                'hasDemoRestaurants'=>$hasDemoRestaurants,
                'envConfigs'=>$this->getCurrentEnv(),
                'showMultiLanguageMigration'=>env('ENABLE_MILTILANGUAGE_MENUS', false) && ! file_exists(storage_path('multilanguagemigrated')),
                ]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('settings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = "\n";
        $str .= file_get_contents($envFile);
        $str .= "\n"; // In case the searched variable is in the last line without \n

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                if ($envValue == trim($envValue) && strpos($envValue, ' ') !== false) {
                    $envValue = '"'.$envValue.'"';
                }

                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if ((! $keyPosition && $keyPosition != 0) || ! $endOfLinePosition || ! $oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 1, -1);
        if (! file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (config('settings.is_demo') | config('settings.is_demo')) {
            //Demo, don;t allow
            return redirect()->route('settings.index')->withStatus(__('Settings not allowed to be updated in DEMO mode!'));
        }

        //$newEnvs = array_merge($this->getCurrentEnv(), $request->env);
        //dd($newEnvs);
        $this->setEnvironmentValue($request->env);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Cache::flush();

        $settings = Settings::find($id);

        $settings->site_name = strip_tags($request->site_name);
        $settings->description = strip_tags($request->site_description);
        $settings->header_title = $request->header_title;
        $settings->header_subtitle = $request->header_subtitle;
        $settings->facebook = strip_tags($request->facebook) ? strip_tags($request->facebook) : '';
        $settings->instagram = strip_tags($request->instagram) ? strip_tags($request->instagram) : '';
        $settings->playstore = strip_tags($request->playstore) ? strip_tags($request->playstore) : '';
        $settings->appstore = strip_tags($request->appstore) ? strip_tags($request->appstore) : '';
        $settings->typeform = strip_tags($request->typeform) ? strip_tags($request->typeform) : '';
        $settings->mobile_info_title = strip_tags($request->mobile_info_title) ? strip_tags($request->mobile_info_title) : '';
        $settings->mobile_info_subtitle = strip_tags($request->mobile_info_subtitle) ? strip_tags($request->mobile_info_subtitle) : '';
        $settings->delivery = (float) $request->delivery;
        //$settings->order_options = $request->order_options;

        fwrite(fopen(__DIR__.'/../../../public/byadmin/front.js', 'w'), str_replace('tagscript', 'script', $request->jsfront));
        fwrite(fopen(__DIR__.'/../../../public/byadmin/back.js', 'w'), str_replace('tagscript', 'script', $request->jsback));
        fwrite(fopen(__DIR__.'/../../../public/byadmin/front.css', 'w'), $request->cssfront);
        fwrite(fopen(__DIR__.'/../../../public/byadmin/back.css', 'w'), $request->cssback);

        if ($request->hasFile('site_logo')) {
            $settings->site_logo = $this->saveImageVersions(
                $this->imagePath,
                $request->site_logo,
                [
                    ['name'=>'logo', 'type'=>'png'],
                ]
            );
        }

        if ($request->hasFile('site_logo_dark')) {
            $settings->site_logo_dark = $this->saveImageVersions(
                $this->imagePath,
                $request->site_logo_dark,
                [
                    ['name'=>'site_logo_dark', 'type'=>'png'],
                ]
            );
        }

        if ($request->hasFile('search')) {
            $settings->search = $this->saveImageVersions(
                $this->imagePath,
                $request->search,
                [
                    ['name'=>'cover'],
                ]
            );
        }

        if ($request->hasFile('restorant_details_image')) {
            $settings->restorant_details_image = $this->saveImageVersions(
                $this->imagePath,
                $request->restorant_details_image,
                [
                    ['name'=>'large', 'w'=>590, 'h'=>400],
                    ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                ]
            );
        }

        //restorant_details_cover_image
        if ($request->hasFile('restorant_details_cover_image')) {
            $settings->restorant_details_cover_image = $this->saveImageVersions(
                $this->imagePath,
                $request->restorant_details_cover_image,
                [
                    ['name'=>'cover', 'w'=>2000, 'h'=>1000],
                ]
            );
        }

        if ($request->hasFile('qrdemo')) {
            $imDemo = Image::make($request->qrdemo->getRealPath())->fit(512, 512);
            $imDemo->save(public_path().'/impactfront/img/qrdemo.jpg');
        }

        $images = [
            public_path().'/impactfront/img/flayer.png',
            public_path().'/impactfront/img/menubuilder.jpg',
            public_path().'/impactfront/img/qr_image_builder.jpg',
            public_path().'/impactfront/img/mobile_pwa.jpg',
            public_path().'/impactfront/img/localorders.jpg',
            public_path().'/impactfront/img/payments.jpg',
            public_path().'/impactfront/img/customerlog.jpg',
        ];

        for ($i = 0; $i < 7; $i++) {
            if ($request->hasFile('ftimig'.$i)) {
                chmod($images[$i], 0777);
                //dd($request->all()['ftimig'.$i]);
                $imDemo = Image::make($request->all()['ftimig'.$i]->getRealPath())->fit(480, 320);
                $imDemo->save($images[$i]);
            }
        }

        if ($request->hasFile('favicons')) {
            $imAC256 = Image::make($request->favicons->getRealPath())->fit(256, 256);
            $imgAC192 = Image::make($request->favicons->getRealPath())->fit(192, 192);
            $imgMS150 = Image::make($request->favicons->getRealPath())->fit(150, 150);

            $imgApple = Image::make($request->favicons->getRealPath())->fit(120, 120);
            $img32 = Image::make($request->favicons->getRealPath())->fit(32, 32);
            $img16 = Image::make($request->favicons->getRealPath())->fit(16, 16);

            $imAC256->save(public_path().'/android-chrome-256x256.png');
            $imgAC192->save(public_path().'/android-chrome-192x192.png');
            $imgMS150->save(public_path().'/mstile-150x150.png');

            $imgApple->save(public_path().'/apple-touch-icon.png');
            $img32->save(public_path().'/favicon-32x32.png');
            $img16->save(public_path().'/favicon-16x16.png');
        }

        $settings->update();

        return redirect()->route('settings.index')->withStatus(__('Settings successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
