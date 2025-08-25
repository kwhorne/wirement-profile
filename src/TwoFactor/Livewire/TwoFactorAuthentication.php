<?php

namespace Wirement\Profile\TwoFactor\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthentication extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?string $code = '';
    public ?string $confirmationCode = '';
    public bool $showingQrCode = false;
    public bool $showingConfirmation = false;
    public bool $showingRecoveryCodes = false;

    public function mount(): void
    {
        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
    }

    public function enableTwoFactorAuthentication(): void
    {
        Auth::user()->enableTwoFactorAuthentication();

        $this->showingQrCode = true;
        $this->showingConfirmation = true;

        Notification::make()
            ->title('Two-factor authentication has been enabled.')
            ->success()
            ->send();
    }

    public function confirmTwoFactorAuthentication(): void
    {
        $confirmed = Auth::user()->confirmTwoFactorAuthentication($this->confirmationCode);

        if ($confirmed) {
            $this->showingQrCode = false;
            $this->showingConfirmation = false;
            $this->showingRecoveryCodes = true;
            $this->confirmationCode = '';

            Notification::make()
                ->title('Two-factor authentication confirmed and enabled successfully.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('The provided two factor authentication code was invalid.')
                ->danger()
                ->send();
        }
    }

    public function regenerateRecoveryCodes(): void
    {
        Auth::user()->regenerateRecoveryCodes();

        $this->showingRecoveryCodes = true;

        Notification::make()
            ->title('Recovery codes have been regenerated.')
            ->success()
            ->send();
    }

    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactorAuthentication(): void
    {
        Auth::user()->disableTwoFactorAuthentication();

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;

        Notification::make()
            ->title('Two-factor authentication has been disabled.')
            ->success()
            ->send();
    }

    public function confirmationForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('confirmationCode')
                    ->label('Code')
                    ->placeholder('Enter the six digit code from your authenticator application')
                    ->required()
                    ->maxLength(6)
                    ->minLength(6)
                    ->numeric(),
            ]);
    }

    public function confirmAction(): Action
    {
        return Action::make('confirm')
            ->label('Confirm')
            ->action('confirmTwoFactorAuthentication')
            ->color('primary');
    }

    public function enableAction(): Action
    {
        return Action::make('enable')
            ->label('Enable')
            ->action('enableTwoFactorAuthentication')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Enable Two Factor Authentication')
            ->modalDescription('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.');
    }

    public function regenerateCodesAction(): Action
    {
        return Action::make('regenerateCodes')
            ->label('Regenerate Recovery Codes')
            ->action('regenerateRecoveryCodes')
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading('Regenerate Recovery Codes')
            ->modalDescription('These recovery codes can be used to recover access to your account if your two factor authentication device is lost.');
    }

    public function showCodesAction(): Action
    {
        return Action::make('showCodes')
            ->label('Show Recovery Codes')
            ->action('showRecoveryCodes')
            ->color('gray');
    }

    public function disableAction(): Action
    {
        return Action::make('disable')
            ->label('Disable')
            ->action('disableTwoFactorAuthentication')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Disable Two Factor Authentication')
            ->modalDescription('When two factor authentication is disabled, you will no longer be prompted for a token during authentication.');
    }

    public function render()
    {
        return view('wirement-profile::livewire.two-factor-authentication');
    }
}
