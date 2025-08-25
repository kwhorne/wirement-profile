<?php

namespace Wirement\Profile\TwoFactor\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorChallenge extends SimplePage implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'wirement-profile::pages.two-factor-challenge';

    public ?string $code = '';
    public ?string $recovery_code = '';

    public function mount(): void
    {
        if (! Auth::user()->hasEnabledTwoFactorAuthentication()) {
            return redirect()->intended(filament()->getUrl());
        }
    }

    public function authenticate(): void
    {
        $user = Auth::user();

        if (filled($this->code)) {
            if ($user->validateTwoFactorCode($this->code)) {
                $this->confirmTwoFactorAuthentication();
                return;
            }
        } elseif (filled($this->recovery_code)) {
            if (in_array($this->recovery_code, $user->recoveryCodes())) {
                $user->replaceRecoveryCode($this->recovery_code);
                $this->confirmTwoFactorAuthentication();
                return;
            }
        }

        throw ValidationException::withMessages([
            'code' => [__('The provided two factor authentication code was invalid.')],
        ]);
    }

    protected function confirmTwoFactorAuthentication(): void
    {
        session(['two_factor_confirmed_at' => now()]);

        Notification::make()
            ->title('Two-factor authentication verified successfully.')
            ->success()
            ->send();

        return redirect()->intended(filament()->getUrl());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Authentication Code')
                    ->placeholder('Enter your six-digit authentication code')
                    ->maxLength(6)
                    ->minLength(6)
                    ->numeric()
                    ->autofocus(),
                
                TextInput::make('recovery_code')
                    ->label('Recovery Code')
                    ->placeholder('Or enter a recovery code')
                    ->maxLength(21),
            ]);
    }

    public function authenticateAction(): Action
    {
        return Action::make('authenticate')
            ->label('Verify')
            ->submit('authenticate')
            ->color('primary');
    }

    public function getTitle(): string
    {
        return 'Two-Factor Authentication';
    }

    public function getHeading(): string
    {
        return 'Two-Factor Authentication';
    }

    public function getSubheading(): ?string
    {
        return 'Please confirm access to your account by entering the authentication code provided by your authenticator application.';
    }
}
