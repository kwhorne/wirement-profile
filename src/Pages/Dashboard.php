<?php

namespace Wirement\Profile\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string $routePath = '/dashboard';

    public function getColumns(): int | array
    {
        return 1;
    }
}
