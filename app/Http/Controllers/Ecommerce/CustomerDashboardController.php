<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        return view('ecommerce.dashboard.index', compact('customer'));
    }

    /**
     * Show the customer profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        
        return view('ecommerce.dashboard.profile', compact('customer'));
    }
}