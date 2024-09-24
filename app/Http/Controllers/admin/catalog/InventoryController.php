<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{

    public function index()
    {
        return view('dashboard.catalog.inventory.index');
    }
}
