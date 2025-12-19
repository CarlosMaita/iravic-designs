<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HelpController extends Controller
{
    /**
     * Display the help page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get categories for the header navigation
        try {
            $categories = Category::withCount('products')->orderBy('id')->get();
        } catch (\Exception $e) {
            $categories = collect();
        }
        
        return view('ecommerce.help.index', compact('categories'));
    }
}
