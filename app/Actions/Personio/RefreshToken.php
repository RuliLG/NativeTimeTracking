<?php

namespace App\Actions\Personio;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class RefreshToken
{
    use AsAction;

    public function handle(Response $response)
    {
        $token = Str::after($response->header('Authorization'), 'Bearer ');
        Cache::put('personio-token', $token, now()->addHour());

        return $token;
    }
}
