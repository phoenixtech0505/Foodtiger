<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => auth()->user()->notifications,
            'status' => true,
        ]);
    }
}
