<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // Constructor intentionally empty - sales functionality removed
    }

    public function index(Request $request)
    {
        // Sales and refunds dashboard functionality removed
        // Return simplified dashboard without sales metrics
        return view('dashboard.homepage');
    }
}

