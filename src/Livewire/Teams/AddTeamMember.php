<?php

namespace Wirement\Profile\Livewire\Teams;

use Closure;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Wirement\Profile\Events\InvitingTeamMember;
use Wirement\Profile\WirementProfile;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Wirement\Profile\Mail\TeamInvitation;
use Wirement\Profile\Models\Team;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Unique;

class AddTeamMember extends BaseLivewireComponent
{
    public ?array $data = [];

    public Team $team;

    public function mount(Team $team): void
    {
        $this->team = $team;

        $this->form->fill($this->team->only(['name']));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make(__('wirement-profile::default.add_team_member.section.title'))
                    ->aside()
                    ->visible(fn () => Gate::check('addTeamMember', $this->team))
                    ->description(__('wirement-profile::default.add_team_member.section.description'))
                    ->schema([
                        TextEntry::make('addTeamMemberNotice')
                            ->hiddenLabel()
                            ->state(fn () => __('wirement-profile::default.add_team_member.section.notice')),
                        TextInput::make('email')
                            ->label(__('wirement-profile::default.form.email.label'))
                            ->email()
                            ->required()
                            ->unique(table: WirementProfile::plugin()->teamInvitationModel(), modifyRuleUsing: function (
                                Unique $rule
                            ) {
                                return $rule->where(
                                    WirementProfile::getForeignKeyColumn(WirementProfile::plugin()->teamModel()),
                                    $this->team->id
                                );
                            })
                            ->validationMessages([
                                'unique' => __(
                                    'wirement-profile::default.action.add_team_member.error_message.email_already_invited'
                                ),
                            ])
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    if ($this->team->hasUserWithEmail($value)) {
                                        $fail(
                                            __(
                                                'wirement-profile::default.action.add_team_member.error_message.email_already_invited'
                                            )
                                        );
                                    }
                                },
                            ]),
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
                                        ->descriptions($roles->pluck('description', 'key')),
                                ];
                            }),
                        Actions::make([
                            Action::make('addTeamMember')
                                ->label(__('wirement-profile::default.action.add_team_member.label'))
                                ->action(function () {
                                    $this->addTeamMember($this->team);
                                }),
                        ])->alignEnd(),
                    ]),
            ]);
    }

    public function addTeamMember(Team $team): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->sendRateLimitedNotification($exception);

            return;
        }

        $data = $this->form->getState();

        $email = $data['email'];

        $role = $data['role'];

        InvitingTeamMember::dispatch($team, $email, $role);

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        Mail::to($email)->send(new TeamInvitation($invitation));

        $this->sendNotification(__('wirement-profile::default.notification.team_invitation_sent.success.message'));

        $this->redirect(Filament::getTenantProfileUrl());
    }

    public function render()
    {
        return view('wirement-profile::livewire.teams.add-team-member');
    }
}
