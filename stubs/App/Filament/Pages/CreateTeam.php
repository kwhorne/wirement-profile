<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('Create Team');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel(),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        return app(\App\Actions\wirement-profile\CreateTeam::class)->create(auth()->user(), $data);
    }
}
