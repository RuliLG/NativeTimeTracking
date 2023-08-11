<?php

namespace App\Actions\Personio;

use App\Personio\PersonioClient;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetToken
{
    use AsAction;

    public function handle(array $config, bool $refresh = false)
    {
        if ($refresh) {
            Cache::forget('personio-token');
        }

        return Cache::remember('personio-token', now()->addHour(), function () use ($config) {
            return $this->getToken($config);
        });
    }

    private function getToken(array $config): string
    {
        $token = PersonioClient::make($config)->post('/auth', [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
        ])->throw()->json('data.token');

        return $token;
    }
}
