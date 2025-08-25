<?php

namespace Wirement\Profile\Pages;

use Wirement\Profile\Livewire\Teams\AddTeamMember;
use Wirement\Profile\Livewire\Teams\DeleteTeam;
use Wirement\Profile\Livewire\Teams\PendingTeamInvitations;
use Wirement\Profile\Livewire\Teams\TeamMembers;
use Wirement\Profile\Livewire\Teams\UpdateTeamName;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class EditTeam extends EditTenantProfile
{
    protected string $view = 'wirement-profile::pages.edit-team';

    protected static ?int $navigationSort = 2;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Livewire::make(UpdateTeamName::class)
                ->data(['team' => $this->tenant]),
            Livewire::make(AddTeamMember::class)
                ->data(['team' => $this->tenant]),
            Livewire::make(PendingTeamInvitations::class)
                ->data(['team' => $this->tenant]),
            Livewire::make(TeamMembers::class)
                ->data(['team' => $this->tenant]),
            Livewire::make(DeleteTeam::class)
                ->data(['team' => $this->tenant]),
        ]);
    }

    public static function getLabel(): string
    {
        return __('wirement-profile::default.page.edit_team.title');
    }
}
