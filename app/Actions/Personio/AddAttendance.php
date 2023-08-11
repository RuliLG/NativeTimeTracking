<?php

namespace App\Actions\Personio;

use App\Actions\Personio\GetToken;
use App\DTO\Profile;
use App\DTO\Project;
use App\Personio\PersonioClient;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class AddAttendance
{
    use AsAction;

    public function handle(array $config, Profile $user, Carbon $startTime, Carbon $endTime, Project $project = null, string $comment = null)
    {
        $token = GetToken::run($config, true);
        $response = PersonioClient::make($config, $token)
            ->post('/company/attendances', [
                'attendances' => [
                    [
                        'employee' => $user->id,
                        'date' => $startTime->format('Y-m-d'),
                        'start_time' => $startTime->format('H:i'),
                        'end_time' => $endTime->format('H:i'),
                        'project_id' => $project?->id,
                        'comment' => $comment,
                        'break' => 0,
                    ],
                ],
                'skip_approval' => false,
            ])
            ->json();
        if (! $response['success']) {
            throw new \Exception(Arr::get($response, 'error.detailed_message.0.error_msg'), Arr::get($response, 'error.code'));
        }

        return $response;
    }
}
