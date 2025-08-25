<?php

namespace Wirement\Profile;

use Wirement\Profile\Commands\InstallCommand;
use Wirement\Profile\Livewire\ApiTokens\CreateApiToken;
use Wirement\Profile\Livewire\ApiTokens\ManageApiTokens;
use Wirement\Profile\Livewire\Profile\DeleteAccount;
use Wirement\Profile\Livewire\Profile\LogoutOtherBrowserSessions;
use Wirement\Profile\Livewire\Profile\UpdatePassword;
use Wirement\Profile\Livewire\Profile\UpdateProfileInformation;
use Wirement\Profile\Livewire\Teams\AddTeamMember;
use Wirement\Profile\Livewire\Teams\DeleteTeam;
use Wirement\Profile\Livewire\Teams\PendingTeamInvitations;
use Wirement\Profile\Livewire\Teams\TeamMembers;
use Wirement\Profile\Livewire\Teams\UpdateTeamName;
use Wirement\Profile\Pages\ApiTokens;
use Wirement\Profile\Pages\EditProfile;
use Wirement\Profile\Pages\EditTeam;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wirement\Profile\Commands\InstallWirementProfileCommand;
use Wirement\Profile\TwoFactor\Livewire\TwoFactorAuthentication;
use Wirement\Profile\TwoFactor\Livewire\PasskeyAuthentication;

class WirementProfileServiceProvider extends PackageServiceProvider
{
    public static string $name = 'wirement-profile';

    public static string $viewNamespace = 'wirement-profile';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasConfigFile(static::$name)
            ->hasCommands([
                InstallCommand::class,
                InstallWirementProfileCommand::class,
            ]);

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/2025_08_22_134103_add_profile_photo_column_to_users_table.php' => database_path('migrations/2025_08_22_134103_add_profile_photo_column_to_users_table.php'),
        ], 'wirement-profile-migrations');

        $this->publishes([
            __DIR__ . '/../database/migrations/2025_08_22_134103_create_teams_table.php' => database_path('migrations/2025_08_22_134103_create_teams_table.php'),
        ], 'wirement-profile-team-migrations');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/wirement-profile.php' => config_path('wirement-profile.php'),
        ], 'wirement-profile-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/wirement-profile'),
        ], 'wirement-profile-views');
    }

    public function packageBooted()
    {
        $this->registerLivewireComponents();
        $this->registerTwoFactorAuthentication();
    }

    private function registerLivewireComponents(): void
    {
        /*
         * Profile Components
         */
        Livewire::component('wirement-profile::pages.edit-profile', EditProfile::class);
        Livewire::component(
            'wirement-profile::livewire.profile.update-profile-information',
            UpdateProfileInformation::class
        );
        Livewire::component('wirement-profile::livewire.profile.update-password', UpdatePassword::class);
        Livewire::component(
            'wirement-profile::livewire.profile.logout-other-browser-sessions',
            LogoutOtherBrowserSessions::class
        );
        Livewire::component('wirement-profile::livewire.profile.delete-account', DeleteAccount::class);

        /*
         * Api Token Components
         */
        Livewire::component('wirement-profile::pages.api-tokens', ApiTokens::class);
        Livewire::component('wirement-profile::livewire.api-tokens.create-api-token', CreateApiToken::class);
        Livewire::component('wirement-profile::livewire.api-tokens.manage-api-tokens', ManageApiTokens::class);

        /*
         * Teams Components
         */
        Livewire::component('wirement-profile::pages.edit-teams', EditTeam::class);
        Livewire::component('wirement-profile::livewire.teams.update-team-name', UpdateTeamName::class);
        Livewire::component('wirement-profile::livewire.teams.add-team-member', AddTeamMember::class);
        Livewire::component('wirement-profile::livewire.teams.team-members', TeamMembers::class);
        Livewire::component(
            'wirement-profile::livewire.teams.pending-team-invitations',
            PendingTeamInvitations::class
        );
        Livewire::component('wirement-profile::livewire.teams.delete-team', DeleteTeam::class);
    }

    private function registerTwoFactorAuthentication(): void
    {
        // Register internal Two Factor Authentication components
        Livewire::component('wirement-profile::two-factor-authentication', TwoFactorAuthentication::class);
        Livewire::component('wirement-profile::passkey-authentication', PasskeyAuthentication::class);
    }
}
