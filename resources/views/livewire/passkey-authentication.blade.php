<div class="space-y-6">
    <div class="wirement-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Passkey Authentication</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage your passkeys for secure, passwordless authentication.</p>
            </div>
        </div>

        <div class="space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Passkeys provide a secure and convenient way to sign in without passwords. They use your device's built-in security features like Touch ID, Face ID, or Windows Hello.
            </p>

            @if ($passkeys->count() > 0)
                <!-- Existing Passkeys -->
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-900">Your Passkeys</h4>
                    
                    <div class="space-y-2">
                        @foreach ($passkeys as $passkey)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $passkey->name ?? 'Unnamed Passkey' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Created {{ $passkey->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                
                                <button 
                                    wire:click="deletePasskey({{ $passkey->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    Delete
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- No Passkeys -->
                <div class="text-center py-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2h-6m6 0v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2h6m0 0V7a2 2 0 00-2-2H9a2 2 0 00-2-2v0a2 2 0 00-2 2v.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No passkeys</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first passkey.</p>
                </div>
            @endif

            <!-- Create Passkey Button -->
            <div class="flex justify-center">
                {{ $this->createAction }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('create-passkey', (event) => {
        const { options, name } = event[0];
        
        if (!navigator.credentials) {
            alert('WebAuthn is not supported in this browser.');
            return;
        }

        navigator.credentials.create({
            publicKey: options
        }).then((credential) => {
            // Send the credential back to the server
            Livewire.dispatch('passkey-created', {
                credential: {
                    id: credential.id,
                    rawId: Array.from(new Uint8Array(credential.rawId)),
                    response: {
                        clientDataJSON: Array.from(new Uint8Array(credential.response.clientDataJSON)),
                        attestationObject: Array.from(new Uint8Array(credential.response.attestationObject))
                    },
                    type: credential.type
                },
                name: name
            });
        }).catch((error) => {
            console.error('Passkey creation failed:', error);
            alert('Failed to create passkey: ' + error.message);
        });
    });
});
</script>
@endpush
