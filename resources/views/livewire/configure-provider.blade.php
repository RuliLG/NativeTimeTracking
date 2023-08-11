<div class="h-full flex items-center justify-center">
    <div class="bg-gray-900 w-full max-w-xs p-6 rounded-md">
        <p class="text-center font-bold mb-4">Configuring {{ $provider->name() }}</p>
        {{ $this->form }}
        <x-filament::button wire:click="save" class="mt-6 w-full">
            {{ __('Save configuration') }}
        </x-filament::button>
    </div>
</div>
