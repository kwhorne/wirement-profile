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
use Spatie\LaravelPasskeys\Facades\Passkey;

class PasskeyAuthentication extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?string $passkeyName = '';

    public function mount(): void
    {
        //
    }

    public function createPasskey(): void
    {
        try {
            $passkey = Passkey::generateCreationOptions(Auth::user());
            
            $this->dispatch('create-passkey', [
                'options' => $passkey,
                'name' => $this->passkeyName ?: 'Default Passkey'
            ]);

            $this->passkeyName = '';

            Notification::make()
                ->title('Passkey creation initiated. Please follow your browser prompts.')
                ->info()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to create passkey: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deletePasskey($passkeyId): void
    {
        try {
            Auth::user()->passkeys()->where('id', $passkeyId)->delete();

            Notification::make()
                ->title('Passkey deleted successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to delete passkey: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function passkeyForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('passkeyName')
                    ->label('Passkey Name')
                    ->placeholder('Enter a name for this passkey (optional)')
                    ->maxLength(255),
            ]);
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->label('Create Passkey')
            ->form($this->passkeyForm(new Form()))
            ->action('createPasskey')
            ->color('primary')
            ->modalHeading('Create New Passkey')
            ->modalDescription('Passkeys provide a secure and convenient way to authenticate without passwords.');
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Delete')
            ->action(fn (array $arguments) => $this->deletePasskey($arguments['passkey']))
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete Passkey')
            ->modalDescription('Are you sure you want to delete this passkey? This action cannot be undone.');
    }

    public function render()
    {
        return view('wirement-profile::livewire.passkey-authentication', [
            'passkeys' => Auth::user()->passkeys ?? collect(),
        ]);
    }
}
