<div class="space-y-6">
    <div class="wirement-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Two-Factor Authentication</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add additional security to your account using two-factor authentication.</p>
            </div>
        </div>

        @if (! auth()->user()->hasEnabledTwoFactorAuthentication())
            <!-- Two Factor Authentication is not enabled -->
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. 
                    You may retrieve this token from your phone's Google Authenticator application.
                </p>
                
                {{ $this->enableAction }}
            </div>
        @else
            <!-- Two Factor Authentication is enabled -->
            <div class="space-y-4">
                @if ($showingQrCode)
                    <!-- QR Code -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                        </p>

                        <div class="wirement-2fa-qr inline-block">
                            {!! $this->qrCode !!}
                        </div>
                        <div class="flex justify-center">
                            <div class="p-4 bg-white border rounded-lg">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Setup Key:</p>
                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ decrypt(auth()->user()->two_factor_secret) }}</code>
                        </div>
                    </div>
                @endif

                @if ($showingConfirmation)
                    <!-- Confirmation Form -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Please confirm access to your authenticator application by entering the authentication code provided by the application.
                        </p>

                        <x-filament::button wire:click="enableTwoFactorAuthentication" color="primary" class="wirement-button-primary">
                            Enable Two-Factor Authentication
                        </x-filament::button>
                        <div class="flex justify-end mt-4">
                            {{ $this->confirmAction }}
                        </div>
                    </div>
                @endif

                @if ($showingRecoveryCodes)
                    <!-- Recovery Codes -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Two factor authentication is enabled. Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                        </p>

                        <div class="grid grid-cols-2 gap-2 max-w-md">
                            @foreach ($this->recoveryCodes as $code)
                                <div class="wirement-recovery-code">
                                    {{ $code }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! $showingConfirmation && ! $showingRecoveryCodes)
                    <!-- Enabled State Actions -->
                    <div class="space-y-4">
                        <p class="text-sm text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Two factor authentication is enabled.
                        </p>

                        <div class="flex space-x-2">
                            <x-filament::button wire:click="regenerateRecoveryCodes" color="gray" class="wirement-button-secondary">
                                Regenerate Recovery Codes
                            </x-filament::button>

                            <x-filament::button wire:click="disableTwoFactorAuthentication" color="danger">
                                Disable Two-Factor Authentication
                            </x-filament::button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
