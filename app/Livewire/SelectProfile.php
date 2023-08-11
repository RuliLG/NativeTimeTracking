<?php

namespace App\Livewire;

use App\Models\Configuration;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class SelectProfile extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function placeholder()
    {
        $title = view('components.skeleton', ['class' => 'mb-4 w-[200px] mx-auto h-6']);
        $label = view('components.skeleton', ['class' => 'mb-2 w-[80px] h-4']);
        $select = view('components.skeleton', ['class' => 'mb-6 w-full h-10']);
        $button = view('components.skeleton', ['class' => 'w-full h-10']);
        return '<div class="h-full flex items-center justify-center">
            <div class="bg-gray-900 w-full max-w-xs p-6 rounded-md">' . $title . $label . $select . $button . '</div>
        </div>';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(Configuration::timeTrackingProvider()->selectProfileForm())
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $configuration = Configuration::firstOrFail();
        $config = $configuration->provider_config;
        $config['profile'] = $state['profile'];
        $configuration->provider_config = $config;
        $configuration->save();
        Notification::make()
            ->success()
            ->title('Done!')
            ->send();
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.select-profile', [
            'provider' => Configuration::timeTrackingProvider(),
        ]);
    }
}
