<?php

namespace Wirement\Profile\Pages;

use Filament\Facades\Filament;
use Wirement\Profile\WirementProfile;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    protected static string | null | \BackedEnum $navigationIcon = 'heroicon-o-user-circle';

    protected string $view = 'wirement-profile::pages.edit-profile';

    protected static ?string $navigationLabel = 'Profile';

    public function mount(): void
    {
        parent::mount();

        if ($id = $this->getUser()?->currentTeam?->id) {
            once(fn () => Filament::setTenant(WirementProfile::plugin()->teamModel::find($id)));
        }
    }

    public static function isSimple(): bool
    {
        return false;
    }
}
