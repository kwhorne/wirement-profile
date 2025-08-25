<?php

namespace Wirement\Profile\Listeners;

use Filament\Events\TenantSet;
use Wirement\Profile\WirementProfile;

class SwitchTeam
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantSet $event): void
    {
        if (WirementProfile::plugin()?->hasTeamsFeatures()) {
            $event->getUser()->switchTeam($event->getTenant());
        }
    }
}
