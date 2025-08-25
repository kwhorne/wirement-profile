<?php

namespace Wirement\Profile\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Wirement\Profile\Events\AddingTeam;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('wirement-profile::default.page.create_team.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name'),
        ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $user = Filament::auth()->user();

        if ($user === null) {
            throw new Exception(__('The authenticated user object must be a filament auth model!'));
        }

        AddingTeam::dispatch($user);

        $user->switchTeam(
            $team = $user->ownedTeams()->create([
                'name' => $data['name'],
                'personal_team' => ! $user->currentTeam,
            ])
        );

        return $team;
    }
}
