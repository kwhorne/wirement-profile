@use('Wirement\Profile\wirement-profile')

<x-filament-panels::page>
    @if (WirementProfile::plugin()?->enabledProfileInformationUpdate())
        @livewire(Wirement\Profile\Livewire\Profile\UpdateProfileInformation::class)
    @endif

    @if (WirementProfile::plugin()?->enabledPasswordUpdate())
        @livewire(Wirement\Profile\Livewire\Profile\UpdatePassword::class)
    @endif

    @if (WirementProfile::plugin()?->enabledTwoFactorAuthetication())
        @livewire(\Wirement\Profile\TwoFactor\Livewire\TwoFactorAuthentication::class)
    @endif

    @if (WirementProfile::plugin()?->enabledPasskeyAuthetication())
        @livewire(\Wirement\Profile\TwoFactor\Livewire\PasskeyAuthentication::class)
    @endif

    @if (WirementProfile::plugin()?->enabledLogoutOtherBrowserSessions())
        @livewire(Wirement\Profile\Livewire\Profile\LogoutOtherBrowserSessions::class)
    @endif

    @if (WirementProfile::plugin()?->enabledDeleteAccount())
        @livewire(Wirement\Profile\Livewire\Profile\DeleteAccount::class)
    @endif
</x-filament-panels::page>
