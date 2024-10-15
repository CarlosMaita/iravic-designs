<?php

namespace App\Http\Controllers\admin\sales;

use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    public function index()
    {
        return view('admin.sales.collection.index');
    }
    
}
