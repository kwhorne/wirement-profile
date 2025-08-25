<?php

namespace Wirement\Profile\Livewire\Teams;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Wirement\Profile\Models\Team;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UpdateTeamName extends BaseLivewireComponent
{
    public ?array $data = [];

    public Team $team;

    public function mount(Team $team): void
    {
        $this->team = $team;

        $this->form->fill($team->only(['name']));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('wirement-profile::default.update_team_name.section.title'))
                    ->aside()
                    ->description(__('wirement-profile::default.update_team_name.section.description'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('wirement-profile::default.form.team_name.label'))
                            ->string()
                            ->maxLength(255)
                            ->required(),
                        Actions::make([
                            Action::make('save')
                                ->label(__('wirement-profile::default.action.save.label'))
                                ->action(fn () => $this->updateTeamName($this->team)),
                        ])->alignEnd(),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateTeamName(Team $team): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->sendRateLimitedNotification($exception);

            return;
        }

        $data = $this->form->getState();

        $team->forceFill([
            'name' => $data['name'],
        ])->save();

        $this->sendNotification();
    }

    public function render()
    {
        return view('wirement-profile::livewire.teams.update-team-name');
    }
}
