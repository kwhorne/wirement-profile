<?php

namespace Wirement\Profile\Livewire\Teams;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Wirement\Profile\Models\Team;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DeleteTeam extends BaseLivewireComponent
{
    public Team $team;

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('wirement-profile::default.delete_team.section.title'))
                    ->description(__('wirement-profile::default.delete_team.section.description'))
                    ->aside()
                    ->visible(fn () => Gate::check('delete', $this->team))
                    ->schema([
                        TextEntry::make('notice')
                            ->hiddenLabel()
                            ->state(__('wirement-profile::default.delete_team.section.notice')),
                        Actions::make([
                            Action::make('deleteAccountAction')
                                ->label(__('wirement-profile::default.action.delete_team.label'))
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('wirement-profile::default.delete_team.section.title'))
                                ->modalDescription(__('wirement-profile::default.action.delete_team.notice'))
                                ->modalSubmitActionLabel(__('wirement-profile::default.action.delete_team.label'))
                                ->modalCancelAction(false)
                                ->action(fn () => $this->deleteTeam($this->team)),
                        ]),
                    ]),
            ]);
    }

    public function render(): View
    {
        return view('wirement-profile::livewire.teams.delete-team');
    }

    public function deleteTeam(Team $team): void
    {
        $team->purge();

        $this->sendNotification(__('wirement-profile::default.notification.team_deleted.success.message'));

        redirect()->to(Filament::getCurrentPanel()?->getUrl());
    }
}
