<?php

namespace App\Livewire;

use App\Models\Configuration;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuApp extends Component
{
    public ?string $selectedProvider = null;
    public bool $hasProviderConfig = false;
    public string|int|null $selectedProfile = null;

    public function mount()
    {
        $this->loadState();
    }

    #[On('refresh')]
    public function loadState()
    {
        $configuration = Configuration::firstOrFail();
        $this->selectedProvider = $configuration->provider;
        $this->hasProviderConfig = filled($configuration->provider_config);
        $this->selectedProfile = $configuration->provider_config['profile'] ?? null;
    }

    public function render()
    {
        return view('livewire.menu-app');
    }
}
