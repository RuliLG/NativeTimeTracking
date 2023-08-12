# Native Time Tracking

## Description
This is a tool built with [NativePHP](https://nativephp.com/) to track time spent on projects. It is built on the following stack:

- [NativePHP](https://nativephp.com/)
- [Laravel](https://laravel.com/)
- [Livewire](https://livewire.laravel.com/)
- [Filament](https://filamentphp.com/)

## Installation
To install the app, you just need to run the following commands:

```bash
composer install

npm install
```

## Running the app
To run the app, you just need to run the following commands:

```bash
php artisan native:serve
npm run dev
php artisan native:migrate # only the first time
```

## Supported providers
As of today, this app only works with [Personio](https://www.personio.de/), which is the system used at my work. If you want to add support for another provider, you would need to extend the `App\TimeTrackingProvider\TimeTrackingProvider` class and implement the following methods:

- `public abstract function identifier(): string;`: Identifier of the provider. This is used to identify the provider in the database.
- `public abstract function name(): string;`: Name of the provider. This is used to display the provider in the UI.
- `public abstract function configForm(): array;`: Filament form schema to configure the provider. Add any necessary API keys or credentials here.
- `public abstract function profiles(): array;`: List of profiles that can be used to track time. This is used to display the profiles in the UI.
- `public abstract function projects(): array;`: List of projects that can be used to track time. This is used to display the projects in the UI. If empty then no projects will be used.
- `public abstract function syncAttendances(): void;`: Syncs the attendances from the provider to the database, using the Attendance model.
- `public abstract function track(?Project $project, ?string $description, Carbon $startTime, Carbon $endTime): void;`: Tracks the time in the provider.
