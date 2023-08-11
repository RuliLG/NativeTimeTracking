import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],
}
