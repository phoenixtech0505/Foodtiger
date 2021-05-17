<?php

namespace App\Http\Controllers;

use App\Restorant;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function index()
    {
        $domain = config('app.url');
        $linkToTheMenu = $domain.'/'.config('settings.url_route').'/'.auth()->user()->restorant->subdomain;

        if (config('settings.wildcard_domain_ready')) {
            $linkToTheMenu = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://').auth()->user()->restorant->subdomain.'.'.str_replace('www.', '', $_SERVER['HTTP_HOST']);
        }

        $dataToPass = [
            'url'=>$linkToTheMenu,
            'titleGenerator'=>__('Restaurant QR Generators'),
            'selectQRStyle'=>__('SELECT QR STYLE'),
            'selectQRColor'=>__('SELECT QR COLOR'),
            'color1'=>__('Color 1'),
            'color2'=>__('Color 2'),
            'titleDownload'=>__('QR Downloader'),
            'downloadJPG'=>__('Download JPG'),
            'titleTemplate'=>__('Menu Print template'),
            'downloadPrintTemplates'=>__('Download Print Templates'),
            'templates'=>explode(',', config('settings.templates')),
            'linkToTemplates'=>env('linkToTemplates', '/impactfront/img/templates.zip'),
        ];

        return view('qrsaas.qrgen')->with('data', json_encode($dataToPass));
    }
}
