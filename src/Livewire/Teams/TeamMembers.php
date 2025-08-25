<?php

namespace Wirement\Profile\Livewire\Teams;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Wirement\Profile\Events\TeamMemberUpdated;
use Wirement\Profile\WirementProfile;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Wirement\Profile\Models\Team;
use Wirement\Profile\Role;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class TeamMembers extends BaseLivewireComponent implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public Team $team;

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function table(Table $table): Table
    {
        $model = WirementProfile::plugin()->membershipModel();

        $teamForeignKeyColumn = WirementProfile::getForeignKeyColumn(get_class($this->team));

        return $table
            ->query(fn () => $model::with('user')->where($teamForeignKeyColumn, $this->team->id))
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('profile_photo_url')
                        ->disk(WirementProfile::plugin()?->profilePhotoDisk())
                        ->defaultImageUrl(fn ($record): string => Filament::getUserAvatarUrl($record->user))
                        ->circular()
                        ->size(25)
                        ->grow(false),
                    Tables\Columns\TextColumn::make('user.email'),
                ]),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('updateTeamRole')
                    ->visible(fn ($record): bool => Gate::check('updateTeamMember', $this->team))
                    ->label(fn ($record): string => Role::find($record->role)->name)
                    ->modalWidth('lg')
                    ->modalHeading(__('wirement-profile::default.action.update_team_role.title'))
                    ->modalSubmitActionLabel(__('wirement-profile::default.action.save.label'))
                    ->modalCancelAction(false)
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->schema([
                        Grid::make()
                            ->columns(1)
                            ->schema(function () {
                                $roles = collect(WirementProfile::plugin()?->getTeamRolesAndPermissions());

                                return [
                                    Radio::make('role')
                                        ->hiddenLabel()
                                        ->required()
                                        ->in($roles->pluck('key'))
                                        ->options($roles->pluck('name', 'key'))
                                        ->descriptions($roles->pluck('description', 'key'))
                                        ->default(fn ($record) => $record->role),
                                ];
                            }),
                    ])
                    ->action(fn ($record, array $data) => $this->updateTeamRole($this->team, $record, $data)),
                Action::make('removeTeamMember')
                    ->visible(
                        fn ($record): bool => $this->authUser()->id !== $record->id && Gate::check(
                            'removeTeamMember',
                            $this->team
                        )
                    )
                    ->label(__('wirement-profile::default.action.remove_team_member.label'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->removeTeamMember($this->team, $record)),
                Action::make('leaveTeam')
                    ->visible(fn ($record): bool => $this->authUser()->id === $record->id)
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->color('danger')
                    ->label(__('wirement-profile::default.action.leave_team.label'))
                    ->modalDescription(__('wirement-profile::default.action.leave_team.notice'))
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->leaveTeam($record)),
            ]);
    }

    public function updateTeamRole(Model $team, Model $teamMember, array $data): void
    {
        if (! Gate::check('updateTeamMember', $team)) {
            $this->sendNotification(
                __('wirement-profile::default.notification.permission_denied.cannot_update_team_member'),
                type: 'danger'
            );

            return;
        }

        $team->users()->updateExistingPivot($teamMember->user_id, ['role' => $data['role']]);

        TeamMemberUpdated::dispatch($team->fresh(), $teamMember);

        $this->sendNotification();

        $team->fresh();
    }

    public function removeTeamMember(Team $team, Model $teamMember): void
    {
        if ($teamMember->id === $team->owner->id) {
            $this->sendNotification(
                __('wirement-profile::default.notification.permission_denied.cannot_leave_team'),
                type: 'danger'
            );

            return;
        }

        if (! Gate::check('removeTeamMember', $team)) {
            $this->sendNotification(
                __('wirement-profile::default.notification.permission_denied.cannot_remove_team_member'),
                type: 'danger'
            );

            return;
        }

        $team->removeUser($teamMember->user);

        $this->sendNotification(__('wirement-profile::default.notification.team_member_removed.success.message'));

        $team->fresh();
    }

    public function leaveTeam(Team $team): void
    {
        $teamMember = $this->authUser();

        if ($teamMember->id === $team->owner->id) {
            $this->sendNotification(
                title: __('wirement-profile::default.notification.permission_denied.cannot_leave_team'),
                type: 'danger'
            );

            return;
        }

        $team->removeUser($teamMember);

        $this->sendNotification(__('wirement-profile::default.notification.leave_team.success'));

        $this->redirect(Filament::getHomeUrl());
    }

    public function render()
    {
        return view('wirement-profile::livewire.teams.team-members');
    }
}
