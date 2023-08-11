<?php

namespace App\Actions\Personio;

use App\DTO\Project;
use App\Personio\PersonioClient;
use Lorisleiva\Actions\Concerns\AsAction;

class GetProjects
{
    use AsAction;

    public function handle(array $config)
    {
        $token = GetToken::run($config, refresh: true);
        $response = PersonioClient::make($config, $token)->get('/company/attendances/projects');
        $projects = $response->throw()->json('data');
        $profiles = [];
        foreach ($projects as $project) {
            if (data_get($project, 'attributes.active')) {
                $profiles[] = new Project(data_get($project, 'id'), data_get($project, 'attributes.name'));
            }
        }

        RefreshToken::run($response);

        return $profiles;
    }
}
