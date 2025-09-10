<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "configs";

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    # Methods
    /**
     * Almacena una config en la BD. Se guarda por Key
     */
    public static function getConfig($key)
    {
        $config = Config::where('key', $key)->first();

        if (!$config) {
            $config = Config::create([
                'key'=>$key,
                'value'=> Config::defaultConfig(trim($key))
            ]);
        }

        return $config;
    }

    /**
     * Se almacena contrasena predeterminada para descuentos
     */
    public static function defaultConfig($key)
    {
        $keys = [
            "discount_password" => '123456',
            "usd_to_ves_rate" => '36.50',
            "usd_to_ves_rate_last_update" => ''
        ];

        if (array_key_exists($key, $keys)) {
            return $keys[$key];
        }

        return "";
    }
}
