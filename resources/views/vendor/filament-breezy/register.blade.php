<x-filament-breezy::auth-card action="register">
    <div class="w-full flex justify-center">
        <x-filament::brand />
    </div>

    <div>
        <h2 class="font-bold tracking-tight text-center text-2xl">
            {{ __('filament-breezy::default.registration.heading') }}
        </h2>
    </div>

    {{ $this->form }}

    <x-filament::button type="submit" class="w-full" form="register">
        {{ __('filament-breezy::default.registration.submit.label') }}
    </x-filament::button>
</x-filament-breezy::auth-card>
