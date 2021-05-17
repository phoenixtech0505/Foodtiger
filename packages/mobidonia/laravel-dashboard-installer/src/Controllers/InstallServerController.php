<?php

namespace dacoto\LaravelInstaller\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class InstallServerController extends Controller
{
    /**
     * Check server requirements step
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('installer::steps.server', ['checks' => $this->check()]);
    }

    /**
     * Check requirements
     *
     * @return array
     */
    public function check(): array
    {
        return [
            'php' => version_compare(PHP_VERSION, config('installer.php'), '>'),
            'pdo' => defined('PDO::ATTR_DRIVER_NAME'),
            'mbstring' => extension_loaded('mbstring'),
            'fileinfo' => extension_loaded('fileinfo'),
            'openssl' => extension_loaded('openssl'),
            'tokenizer' => extension_loaded('tokenizer'),
            'json' => extension_loaded('json'),
            'curl' => extension_loaded('curl')
        ];
    }
}
