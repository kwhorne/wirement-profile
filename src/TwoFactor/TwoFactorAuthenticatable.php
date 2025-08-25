<?php

namespace Wirement\Profile\TwoFactor;

use Illuminate\Database\Eloquent\Casts\Attribute;
use PragmaRX\Google2FA\Google2FA;

trait TwoFactorAuthenticatable
{
    /**
     * Get the user's two factor authentication recovery codes.
     */
    public function recoveryCodes(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? json_decode(decrypt($value), true) : [],
            set: fn ($value) => encrypt(json_encode($value)),
        );
    }

    /**
     * Replace the given recovery code with a new one in the user's stored codes.
     */
    public function replaceRecoveryCode(string $code): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => collect($this->recoveryCodes())
                ->reject(fn ($c) => hash_equals($code, $c))
                ->values()
                ->all(),
        ])->save();
    }

    /**
     * Determine if two-factor authentication has been enabled.
     */
    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return ! is_null($this->two_factor_secret) &&
               ! is_null($this->two_factor_confirmed_at);
    }

    /**
     * Get the QR code SVG of the user's two factor authentication QR code URL.
     */
    public function twoFactorQrCodeSvg(): string
    {
        $svg = (new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(192),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        ))->render((new \BaconQrCode\Writer)->writeString($this->twoFactorQrCodeUrl()));

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    /**
     * Get the two factor authentication QR code URL.
     */
    public function twoFactorQrCodeUrl(): string
    {
        return app(Google2FA::class)->getQRCodeUrl(
            config('app.name'),
            $this->email,
            decrypt($this->two_factor_secret)
        );
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactorAuthentication(string $code): bool
    {
        if ($this->validateTwoFactorCode($code)) {
            $this->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            return true;
        }

        return false;
    }

    /**
     * Determine if the given two factor authentication code is valid.
     */
    public function validateTwoFactorCode(string $code): bool
    {
        return app(Google2FA::class)->verifyKey(
            decrypt($this->two_factor_secret), $code
        );
    }

    /**
     * Enable two factor authentication for the user.
     */
    public function enableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => encrypt(app(Google2FA::class)->generateSecretKey()),
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ])->save();
    }

    /**
     * Disable two factor authentication for the user.
     */
    public function disableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ])->save();
    }

    /**
     * Generate new recovery codes for the user.
     */
    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(function () {
            return strtolower(str()->random(10));
        })->all();
    }
}
