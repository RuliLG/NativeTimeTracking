<?php

namespace App\Livewire;

use App\Models\Configuration;
use App\TimeTrackingProvider\TimeTrackingProvider;
use Carbon\Carbon;
use DateTimeZone;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Livewire\Component;

class SelectProvider extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('provider')
                    ->label('Select provider')
                    ->options(
                        collect(TimeTrackingProvider::all())
                            ->mapWithKeys(fn (TimeTrackingProvider $provider) => [$provider->identifier() => $provider->name()])
                            ->all()
                    )
                    ->required(),
                Forms\Components\Select::make('timezone')
                    ->label('Select timezone')
                    ->options(collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($timezone) => [$timezone => $timezone])->all())
                    ->default('Europe/London')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $configuration = Configuration::firstOrFail();
        $configuration->provider = $this->form->getState()['provider'];
        $configuration->timezone = $this->form->getState()['timezone'];
        $configuration->save();

        $this->dispatch('refresh');
    }


    public function render()
    {
        return view('livewire.select-provider');
    }
}
