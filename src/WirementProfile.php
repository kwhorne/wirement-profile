<?php

namespace Wirement\Profile;

use Filament\Panel;
use Illuminate\Support\Str;

class WirementProfile
{
    public static function getForeignKeyColumn(string $class)
    {
        return Str::of($class)->classBasename()->snake()->append('_id')->toString();
    }

    public static function plugin(): WirementProfilePlugin
    {
        return static::panel()
            ->getPlugin('wirement-profile');
    }

    public static function panel(): Panel
    {
        return filament()->getCurrentOrDefaultPanel();
    }
}
