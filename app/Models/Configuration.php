<?php

namespace App\Models;

use App\TimeTrackingProvider\TimeTrackingProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'provider_config' => 'array',
    ];

    public static function timeTrackingProvider()
    {
        $config = self::firstOrFail();
        return TimeTrackingProvider::from($config->provider)
            ->withConfig($config->provider_config);
    }
}
