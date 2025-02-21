<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\DiscountPasswordRequest;
use App\Models\Config;
use App\Services\Images\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    private $filedisk = 'configs';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewany', 'App\Models\Config');
        $discount_password = Config::getConfig('discount_password');
        $name_project = env('APP_NAME');
        $logo_img     = Config::getConfig('logo_img');

        return view('dashboard.config.index')
                ->with( 'nameProject', $name_project)
                ->with( 'logoImg', $logo_img)
                ->with( 'discountPassword', $discount_password);
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

        foreach ($request->except(["_token", "imagen", "logo_img"]) as $key => $value) {
            $config = Config::getConfig($key);
            $config->value = !empty($value) ? $value : '';
            $config->save();
        }

        if ($request->hasFile('logo_img')) {
            // eliminar la imagen anterior
            $logo_img = Config::getConfig('logo_img');
            if (!empty($logo_img->value)) {
                // eliminar la imagen
                ImageService::delete($this->filedisk, $logo_img->value);
            }
            // guardar la nueva imagen
            $file = $request->file('logo_img');
            $url = ImageService::save($this->filedisk, $file);
            $logo_img->value = !empty($url) ? $url : '';
            $logo_img->save();
        } 

        if (!empty($request->name_project)) {
            // editar el nombre del proyecto
            $this->setVariableEnvironment('APP_NAME', $request->name_project);
        }


        flash('La configuracion ha sido actualizada con exito.' )->success();

        return redirect('admin/config/general');
    }

    public function validateDiscountPassword(DiscountPasswordRequest $request)
    {
        return response()->json([
            'success' => true
        ]);
    }


    private function setVariableEnvironment($variable , $value)
    {
        $patch = base_path('.env');
        $content = File::get($patch);
        // Verifica si la variable existe en el archivo .env
        $content = str_replace($variable.'='.env($variable), $variable.'='.$value, $content);
        // Guarda los cambios en el archivo .env
        File::put($patch, $content);
        // Reinicia el cache de configuración para que los cambios surtan efecto
        Artisan::call('config:clear');
        return redirect()->back();
    }
}
