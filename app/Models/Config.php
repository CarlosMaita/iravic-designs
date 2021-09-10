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

    public static function defaultConfig($key)
    {
        $keys = [
            "discount_password" => '123456'
        ];

        if (array_key_exists($key, $keys)) {
            return $keys[$key];
        }

        return "";
    }
}
