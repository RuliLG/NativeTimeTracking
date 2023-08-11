<div class="bg-black min-h-full p-4">
    @if (blank($selectedProvider))
        <livewire:select-provider lazy />
    @elseif(!$hasProviderConfig)
        <livewire:configure-provider lazy />
    @elseif(!$selectedProfile)
        <livewire:select-profile lazy />
    @else
        <div class="grid grid-cols-3 gap-8">
            <div class="col-span-2">
                <livewire:summary lazy />
            </div>
            <livewire:time-tracking lazy />
        </div>
    @endif
</div>
