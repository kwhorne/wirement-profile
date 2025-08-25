@php use Wirement\Profile\wirement-profile; @endphp
<x-filament-panels::page>
    @if (WirementProfile::plugin()?->hasApiTokensFeatures())
        @livewire(Wirement\Profile\Livewire\ApiTokens\CreateApiToken::class)

        @livewire(Wirement\Profile\Livewire\ApiTokens\ManageApiTokens::class)
    @endif
</x-filament-panels::page>
