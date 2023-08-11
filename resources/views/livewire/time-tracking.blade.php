<div class="h-full flex items-center justify-center">
    <div class="bg-gray-900 w-full p-6 rounded-md">
        {{ $this->form }}
        <x-filament::button wire:click="create" class="mt-6 w-full">
            {{ __('Track time') }}
        </x-filament::button>
    </div>
</div>
