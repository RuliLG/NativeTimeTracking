<?php

namespace App\Personio;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PersonioClient
{
    public static function make(array $config, ?string $token = null): PendingRequest
    {
        $headers = [
            'Accept' => 'application/json',
            'X-Personio-Partner-ID' => $config['partner_id'],
            'X-Personio-App-ID' => $config['app_id'],
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return Http::withHeaders($headers)->baseUrl('https://api.personio.de/v1');
    }
}
