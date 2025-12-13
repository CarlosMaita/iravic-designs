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
     * Check if currency module is enabled.
     */
    public static function isCurrencyModuleEnabled()
    {
        if (!config('app.enable_multi_currency')) {
            return false;
        }

        $config = static::getConfig('currency_module_enabled');
        return (bool) $config->value;
    }

    /**
     * Default config values.
     */
    public static function defaultConfig($key)
    {
        return [
            "discount_password" => '123456',
            "usd_to_ves_rate" => '36.50',
            "usd_to_ves_rate_last_update" => '',
            "currency_module_enabled" => '1',
            "admin_notification_email" => env('MAIL_FROM_ADDRESS', 'admin@example.com')
        ][$key] ?? "";
    }

    /**
     * Get admin notification email.
     */
    public static function getAdminNotificationEmail()
    {
        $config = static::getConfig('admin_notification_email');
        return $config->value ?: env('MAIL_FROM_ADDRESS', 'admin@example.com');
    }
}
