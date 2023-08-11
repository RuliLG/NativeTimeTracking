<?php

namespace App\Livewire;

use App\Models\Configuration;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Livewire\Component;

class ConfigureProvider extends Component implements HasForms
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
            ->schema(Configuration::timeTrackingProvider()->configForm())
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $provider = Configuration::timeTrackingProvider();
        $provider->withConfig($state);
        try {
            $provider->profiles();
            $configuration = Configuration::firstOrFail();
            $configuration->provider_config = $state;
            $configuration->save();

            Notification::make()
                ->success()
                ->title('Done! Just missing to select your profile')
                ->send();
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Invalid parameters')
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.configure-provider', [
            'provider' => Configuration::timeTrackingProvider(),
        ]);
    }
}
