<?php

namespace App\TimeTrackingProvider;

use App\Actions\Personio\AddAttendance;
use App\Actions\Personio\GetAttendance;
use App\Actions\Personio\GetEmployees;
use App\Actions\Personio\GetProjects;
use App\Actions\Personio\GetToken;
use App\DTO\Profile;
use App\DTO\Project;
use App\Models\Attendance;
use App\Personio\PersonioClient;
use App\TimeTrackingProvider\TimeTrackingProvider;
use Carbon\Carbon;
use Filament\Forms;
use Illuminate\Database\Eloquent\Collection;

class PersonioTimeTrackingProvider extends TimeTrackingProvider
{
    public function identifier(): string
    {
        return 'personio';
    }

    public function name(): string
    {
        return 'Personio';
    }

    public function configForm(): array
    {
        return [
            Forms\Components\TextInput::make('client_id')
                ->label('Client ID')
                ->required(),
            Forms\Components\TextInput::make('client_secret')
                ->label('Client Secret')
                ->required(),
            Forms\Components\TextInput::make('partner_id')
                ->label('Partner ID')
                ->required(),
            Forms\Components\TextInput::make('app_id')
                ->label('App ID')
                ->required(),
        ];
    }

    public function profiles(): array
    {
        $profiles = GetEmployees::make()->handle($this->configuration);
        return $profiles;
    }

    public function projects(): array
    {
        $projects = GetProjects::make()->handle($this->configuration);
        return $projects;
    }

    public function syncAttendances(): void
    {
        GetAttendance::make()->handle(
            $this->configuration,
            $this->configuration['profile'],
            today()->subWeek()->format('Y-m-d'),
            today()->format('Y-m-d'),
        );
    }

    public function track(?Project $project, ?string $description, Carbon $startTime, Carbon $endTime): void
    {
        AddAttendance::make()->handle(
            config: $this->configuration,
            user: new Profile($this->configuration['profile'], '-'),
            startTime: $startTime,
            endTime: $endTime,
            project: $project,
            comment: $description,
        );
    }
}
