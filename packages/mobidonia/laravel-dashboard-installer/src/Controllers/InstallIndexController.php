<?php

namespace dacoto\LaravelInstaller\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use JsonException;

class InstallIndexController extends Controller
{
    /**
     * Welcome step
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('installer::steps.welcome');
    }

    /**
     * Success installed
     *
     * @return Application|Factory|RedirectResponse|View
     * @throws JsonException
     */
    public function finish()
    {
        if (
            empty(env('APP_KEY')) ||
            !DB::connection()->getPdo() ||
            in_array(false, (new InstallServerController())->check(), true) ||
            in_array(false, (new InstallFolderController())->check(), true)
        ) {
            return redirect()->route('LaravelInstaller::install.database');
        }
        $path = (string) url('/');
        $data = json_encode([
            'date' => date('Y/m/d h:i:s')
        ], JSON_THROW_ON_ERROR);
        file_put_contents(storage_path('installed'), $data, FILE_APPEND | LOCK_EX);
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return view('installer::steps.finish', ['path' => $path]);
    }
}
