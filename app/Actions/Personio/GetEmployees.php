<?php

namespace App\Actions\Personio;

use App\DTO\Profile;
use App\Models\User;
use App\Personio\PersonioClient;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEmployees
{
    use AsAction;

    public function handle(array $config)
    {
        $token = GetToken::run($config, refresh: true);
        $response = PersonioClient::make($config, $token)->get('/company/employees', [
            'limit' => 200,
        ]);
        $employees = $response->throw()->json('data');
        $profiles = [];
        foreach ($employees as $employee) {
            $profiles[] = new Profile(data_get($employee, 'attributes.id.value'), data_get($employee, 'attributes.email.value'));
        }

        RefreshToken::run($response);

        return $profiles;
    }
}
