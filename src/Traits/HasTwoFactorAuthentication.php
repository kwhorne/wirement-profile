<?php

namespace Wirement\Profile\Traits;

use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Wirement\Profile\TwoFactor\TwoFactorAuthenticatable;

trait HasTwoFactorAuthentication
{
    use TwoFactorAuthenticatable;
    use HasPasskeys;

    /**
     * Determine if two-factor authentication is enabled for the user.
     */
    public function hasTwoFactorAuthenticationEnabled(): bool
    {
        return $this->hasEnabledTwoFactorAuthentication();
    }

    /**
     * Determine if the user has passkeys enabled.
     */
    public function hasPasskeysEnabled(): bool
    {
        return $this->passkeys()->exists();
    }

    /**
     * Get the user's two-factor authentication recovery codes.
     */
    public function getTwoFactorRecoveryCodes(): array
    {
        return $this->recoveryCodes();
    }

    /**
     * Replace the given recovery code with a new one in the user's stored codes.
     */
    public function replaceRecoveryCode(string $code): void
    {
        parent::replaceRecoveryCode($code);
    }
}
