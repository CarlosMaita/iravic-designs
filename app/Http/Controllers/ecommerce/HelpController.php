<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Display the help page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('ecommerce.help.index');
    }
}
