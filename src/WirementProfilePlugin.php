<?php

namespace Wirement\Profile;

use Filament\Contracts\Plugin;
use Filament\Events\TenantSet;
use Wirement\Profile\Concerns\HasApiTokensFeatures;
use Wirement\Profile\Concerns\HasProfileFeatures;
use Wirement\Profile\Concerns\HasTeamsFeatures;
use Wirement\Profile\Listeners\SwitchTeam;
use Wirement\Profile\Models\Team;
use Wirement\Profile\Pages\ApiTokens;
use Wirement\Profile\Pages\Auth\Register;
use Wirement\Profile\Pages\CreateTeam;
use Wirement\Profile\Pages\EditProfile;
use Wirement\Profile\Pages\EditTeam;
use Wirement\Profile\Policies\TeamPolicy;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Wirement\Profile\TwoFactor\Middleware\TwoFactorChallenge;
use Wirement\Profile\TwoFactor\Middleware\ForceTwoFactorSetup;

class WirementProfilePlugin implements Plugin
{
    use EvaluatesClosures;
    use HasApiTokensFeatures;
    use HasProfileFeatures;
    use HasTeamsFeatures;

    protected bool $enableTwoFactorAuthentication = true;
    protected bool $forceTwoFactorSetup = false;
    protected bool $requirePasswordForSetup = true;
    protected bool $showTwoFactorMenuItem = true;
    protected string $twoFactorMenuLabel = '2FA';
    protected string $twoFactorMenuIcon = 'heroicon-s-key';

    public function getId(): string
    {
        return 'wirement-profile';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        return filament(app(static::class)->getId());
    }

    public function enableTwoFactorAuthentication(bool $condition = true): static
    {
        $this->enableTwoFactorAuthentication = $condition;
        return $this;
    }

    public function enablePasskeyAuthentication(bool $condition = true): static
    {
        $this->enableTwoFactorAuthentication($condition, false, $condition, false);
        return $this;
    }

    public function forceTwoFactorSetup(bool $condition = false, bool $requiresPassword = true): static
    {
        $this->forceTwoFactorSetup = $condition;
        $this->requirePasswordForSetup = $requiresPassword;
        return $this;
    }

    public function addTwoFactorMenuItem(
        bool $condition = true,
        string $label = '2FA',
        string $icon = 'heroicon-s-key'
    ): static {
        $this->showTwoFactorMenuItem = $condition;
        $this->twoFactorMenuLabel = $label;
        $this->twoFactorMenuIcon = $icon;
        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->homeUrl(fn () => str(filament()->getCurrentOrDefaultPanel()->getUrl())->append('/dashboard'))
            ->profile(EditProfile::class);

        // Two-Factor Authentication is now handled internally
        // Middleware and components are registered in the service provider

        // Register API Tokens if enabled
        if ($this->hasApiTokensFeatures()) {
            $panel
                ->pages([ApiTokens::class])
                ->userMenuItems([$this->apiTokenMenuItem($panel)]);
        }

        // Register Teams if enabled
        if ($this->hasTeamsFeatures()) {
            $panel
                ->registration(Register::class)
                ->tenant($this->teamModel())
                ->tenantRegistration(CreateTeam::class)
                ->tenantProfile(EditTeam::class)
                ->routes(fn () => $this->teamsRoutes());
        }
    }

    public function boot(Panel $panel): void
    {
        // Listen and switch team if tenant was changed
        Event::listen(TenantSet::class, SwitchTeam::class);

        // Register team policies
        Gate::policy(Team::class, TeamPolicy::class);
    }

    protected function enabledTwoFactorAuthetication(): bool
    {
        return $this->enableTwoFactorAuthentication;
    }

    protected function enabledPasskeyAuthetication(): bool
    {
        return $this->enablePasskeyAuthentication;
    }

    protected function forceTwoFactorAuthetication(): bool
    {
        return $this->forceTwoFactorSetup;
    }

    protected function requiresPasswordForAuthenticationSetup(): bool
    {
        return $this->requirePasswordForSetup;
    }
}
