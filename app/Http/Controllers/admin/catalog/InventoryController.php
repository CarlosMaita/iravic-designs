<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function index()
    {
        return view('dashboard.catalog.inventory.index');
    }

    public function download(){
        // TODO: Implement the download of the inventory
        return response()->download(public_path('exports/inventory.xlsx'), 'inventory.xlsx');
    }

    public function upload(Request $request){

    }
}
