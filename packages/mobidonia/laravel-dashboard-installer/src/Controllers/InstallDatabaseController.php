<?php

namespace dacoto\LaravelInstaller\Controllers;

use dacoto\LaravelInstaller\Support\EnvEditor;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class InstallDatabaseController extends Controller
{
    /**
     * Set database settings
     *
     * @return Application|Factory|RedirectResponse|View
     */
    public function database()
    {
        if (
            in_array(false, (new InstallServerController())->check(), true) ||
            in_array(false, (new InstallFolderController())->check(), true)
        ) {
            return redirect()->route('LaravelInstaller::install.folders');
        }
        return view('installer::steps.database');
    }

    /**
     * Test database and set keys in .env
     *
     * @param  Request  $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function setDatabase(Request $request)
    {
        /*$settings = config('database.connections.mysql');
        config([
            'database' => [
                'default' => 'mysql',
                'connections' => [
                    'mysql' => array_merge($settings, [
                        'driver' => 'mysql',
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                        'prefix' => $request->input('database_prefix'),
                    ]),
                ],
            ],
        ]);*/
        try {
            
            //DB
            EnvEditor::setEnv('DB_HOST', $request->input('database_hostname'));
            EnvEditor::setEnv('DB_PORT', $request->input('database_port'));
            EnvEditor::setEnv('DB_DATABASE', $request->input('database_name'));
            EnvEditor::setEnv('DB_USERNAME', $request->input('database_username'));
            EnvEditor::setEnv('DB_PASSWORD', $request->input('database_password'));
            EnvEditor::setEnv('DB_PREFIX', $request->input('database_prefix'));

            //AUTH
            //EnvEditor::setEnv('AUTH0_DOMAIN', $request->input('auth0domain'));
            //EnvEditor::setEnv('AUTH0_CLIENT_ID', $request->input('auth0clientid'));
            //EnvEditor::setEnv('AUTH0_CLIENT_SECRET', $request->input('auth0clientsecret'));
            EnvEditor::setEnv('ADMIN_EMAIL', $request->input('adminemail'));
            EnvEditor::setEnv('ADMIN_PASSWORD', $request->input('adminpassword'));

            

            //Basics
            EnvEditor::setEnv('APP_NAME', $request->input('projectname'));
            EnvEditor::setEnv('APP_URL', $request->input('projecturl'));



            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Cache::flush();
            //DB::connection()->getPdo();
            
            return redirect()->route('LaravelInstaller::install.migrations');
        } catch (Exception $e) {
            return view('installer::steps.database', ['values' =>[], 'error' => $e->getMessage()]);
        }
    }

    /**
     * Success database connection
     *
     * @return Application|Factory|RedirectResponse|View
     */
    public function migrations()
    {
        if (
            !DB::connection()->getPdo() ||
            in_array(false, (new InstallServerController())->check(), true) ||
            in_array(false, (new InstallFolderController())->check(), true)
        ) {
            return redirect()->route('LaravelInstaller::install.database');
        }
        return view('installer::steps.migrations');
    }

    /**
     * Run laravel migrations & seeders
     *
     * @return Application|Factory|RedirectResponse|View
     */
    public function runMigrations()
    {
        if (
            !DB::connection()->getPdo() ||
            in_array(false, (new InstallServerController())->check(), true) ||
            in_array(false, (new InstallFolderController())->check(), true)
        ) {
            return redirect()->route('LaravelInstaller::install.database');
        }
        try {
            Artisan::call('migrate', ['--seed' => true]);
            return redirect()->route('LaravelInstaller::install.keys');
        } catch (Exception $e) {
            return view('installer::steps.migrations', ['error' => $e->getMessage() ?: 'An error occurred while executing migrations']);
        }
    }
}
