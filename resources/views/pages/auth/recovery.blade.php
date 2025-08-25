<x-filament-panels::page.simple>
    <x-slot name="subheading">
        {{ __('wirement-profile::default.form.or.label') }}
        {{ $this->challengeAction }}
    </x-slot>

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}
    </x-filament-panels::form>

</x-filament-panels::page.simple>
