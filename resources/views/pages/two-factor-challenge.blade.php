<x-filament-panels::page.simple>
    <div class="fi-simple-page">
        <div class="fi-simple-header mb-6">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                {{ $this->getHeading() }}
            </h1>
            
            @if ($subheading = $this->getSubheading())
                <p class="fi-simple-header-subheading mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ $subheading }}
                </p>
            @endif
        </div>

        <div class="fi-simple-main">
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Can't access your authenticator app? Use one of your recovery codes instead.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
