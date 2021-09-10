<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewany', 'App\Models\Config');
        $discount_password = Config::getConfig('discount_password');

        return view('dashboard.config.index')
                ->withDiscountPassword($discount_password);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', 'App\Models\Config');

        foreach ($request->except(["_token", "imagen"]) as $key => $value) {
            $config = Config::getConfig($key);
            $config->value = !empty($value) ? $value : '';
            $config->save();
        }

        flash('La configuracion ha sido actualizada con exito.' )->success();

        return redirect('admin/config/general');
    }
}
