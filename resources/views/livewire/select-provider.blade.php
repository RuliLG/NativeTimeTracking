<div class="h-full flex items-center justify-center">
    <div class="bg-gray-900 w-full max-w-xs p-6 rounded-md">
        {{ $this->form }}
        <x-filament::button wire:click="create" class="mt-6 w-full">
            {{ __('Select provider') }}
        </x-filament::button>
    </div>
</div>
