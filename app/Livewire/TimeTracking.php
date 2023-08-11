<?php

namespace App\Livewire;

use App\Actions\Personio\AddAttendance;
use App\DTO\Project;
use App\Models\Configuration;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Livewire\Component;

class TimeTracking extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function placeholder()
    {
        return '<div class="h-full flex items-center justify-center">
            <div class="bg-gray-900 w-full p-6 rounded-md">
                ' . view('components.skeleton', ['class' => 'w-[60px] h-5']) . '
                ' . view('components.skeleton', ['class' => 'mt-2 h-10']) . '
                ' . view('components.skeleton', ['class' => 'mt-6 w-[50px] h-4']) . '
                ' . view('components.skeleton', ['class' => 'mt-2 h-10']) . '
                ' . view('components.skeleton', ['class' => 'mt-6 w-[40px] h-4']) . '
                ' . view('components.skeleton', ['class' => 'mt-2 h-10']) . '
                ' . view('components.skeleton', ['class' => 'mt-6 w-[50px] h-4']) . '
                ' . view('components.skeleton', ['class' => 'mt-2 h-[72px]']) . '
                ' . view('components.skeleton', ['class' => 'mt-6 h-10']) . '
            </div>
        </div>';
    }

    public function form(Form $form): Form
    {
        $configuration = Configuration::firstOrFail();
        $provider = Configuration::timeTrackingProvider();
        $projects = $provider->projects();
        $projectSelector = empty($projects) ? [] : [
            Forms\Components\Select::make('project')
                ->label('Select project')
                ->searchable()
                ->options(collect($provider->projects())->pluck('name', 'id')->all())
                ->required(),
        ];
        return $form
            ->schema([
                ...$projectSelector,
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Start time')
                            ->seconds(false)
                            ->required()
                            ->default(date('H:i', round(time() / (15 * 60)) * (15 * 60) + now()->setTimezone($configuration->timezone)->offset))
                            ->minutesStep(15)
                            ->reactive(),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('End time')
                            ->seconds(false)
                            ->required()
                            ->minDate(function ($get) {
                                $time = explode(':', $get('start_time'));
                                return Carbon::createFromTime($time[0], $time[1])->addMinutes(15);
                            })
                            ->minutesStep(15),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Comment')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create()
    {
        $data = $this->form->getState();
        $provider = Configuration::timeTrackingProvider();
        try {
            $provider->track(
                project: isset($data['project']) ? new Project($data['project'], '-') : null,
                description: $data['description'],
                startTime: Carbon::createFromTime(...explode(':', $data['start_time'])),
                endTime: Carbon::createFromTime(...explode(':', $data['end_time'])),
            );
            Notification::make()
                ->success()
                ->title('Done!')
                ->send();
            $this->dispatch('resync');
            $this->form->fill([
                'start_time' => $data['end_time'],
                'end_time' => null,
                'comment' => '',
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.time-tracking');
    }
}
