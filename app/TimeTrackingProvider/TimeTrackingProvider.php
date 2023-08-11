<?php

namespace App\TimeTrackingProvider;

use App\DTO\Profile;
use App\DTO\Project;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;

abstract class TimeTrackingProvider
{
    protected ?array $configuration;

    public abstract function identifier(): string;

    public abstract function name(): string;

    public abstract function configForm(): array;

    public function selectProfileForm(): array
    {
        return [
            Forms\Components\Select::make('profile')
                ->label('Profile')
                ->searchable()
                ->options(collect($this->profiles())->pluck('name', 'id'))
                ->required(),
        ];
    }

    public abstract function profiles(): array;

    public abstract function projects(): array;

    public abstract function syncAttendances(): void;

    public function attendanceOfThisWeek()
    {
        return Attendance::currentWeek()->get();
    }

    public function attendanceOfToday()
    {
        return Attendance::today()->get()->sortByDesc('start_time');
    }

    public function timeTrackedToday(): int
    {
        return $this->attendanceOfToday()->sum('duration');
    }

    public function timeTrackedThisWeek(): int
    {
        return $this->attendanceOfThisWeek()->sum('duration');
    }

    public abstract function track(?Project $project, ?string $description, Carbon $startTime, Carbon $endTime): void;

    public function withConfig(?array $config): static
    {
        $this->configuration = $config;

        return $this;
    }

    public static function all(): array
    {
        return collect(File::allFiles(app_path('TimeTrackingProvider')))
            ->map(fn ($file) => '\\App\\TimeTrackingProvider\\' . $file->getBasename('.php'))
            ->filter(fn ($file) => class_exists($file) && ! (new \ReflectionClass($file))->isAbstract())
            ->map(fn ($file) => new $file)
            ->all();
    }

    public static function from(string $identifier): TimeTrackingProvider
    {
        return collect(self::all())
            ->filter(fn (TimeTrackingProvider $provider) => $provider->identifier() === $identifier)
            ->firstOrFail();
    }
}
