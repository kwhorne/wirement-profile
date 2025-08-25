<?php

namespace Wirement\Profile\Livewire\Profile;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Wirement\Profile\WirementProfile;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DeleteAccount extends BaseLivewireComponent
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('wirement-profile::default.delete_account.section.title'))
                    ->description(__('wirement-profile::default.delete_account.section.description'))
                    ->aside()
                    ->schema([
                        TextEntry::make('deleteAccountNotice')
                            ->hiddenLabel()
                            ->state(fn () => __('wirement-profile::default.delete_account.section.notice')),
                        Actions::make([
                            Action::make('deleteAccount')
                                ->label(__('wirement-profile::default.action.delete_account.label'))
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('wirement-profile::default.delete_account.section.title'))
                                ->modalDescription(__('wirement-profile::default.action.delete_account.notice'))
                                ->modalSubmitActionLabel(__('wirement-profile::default.action.delete_account.label'))
                                ->modalCancelAction(false)
                                ->form([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label(__('wirement-profile::default.form.password.label'))
                                        ->required()
                                        ->currentPassword(),
                                ])
                                ->action(fn (array $data) => $this->deleteAccount()),
                        ]),
                    ])]);
    }

    /**
     * Delete the current user.
     */
    public function deleteAccount(): Redirector | RedirectResponse
    {
        $user = filament()->auth()->user();

        DB::transaction(function () use ($user) {
            if (WirementProfile::plugin()?->hasTeamsFeatures()) {
                $user->teams()->detach();

                $user->ownedTeams->each(function (Team $team) {
                    $this->deletesTeams->delete($team);
                });
            }

            $user->deleteProfilePhoto();

            $user->tokens?->each->delete();

            $user->delete();
        });

        filament()->auth()->logout();

        return redirect(filament()->getLoginUrl());
    }

    public function render(): View
    {
        return view('wirement-profile::livewire.profile.delete-account');
    }
}
