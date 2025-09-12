<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "configs";

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * Retrieve or create a config by key.
     */
    public static function getConfig($key)
    {
        return static::firstOrCreate(
            ['key' => $key],
            ['value' => static::defaultConfig(trim($key))]
        );
    }

    /**
     * Default config values.
     */
    public static function defaultConfig($key)
    {
        return [
            "discount_password" => '123456',
            "usd_to_ves_rate" => '36.50',
            "usd_to_ves_rate_last_update" => ''
        ][$key] ?? "";
    }
}
