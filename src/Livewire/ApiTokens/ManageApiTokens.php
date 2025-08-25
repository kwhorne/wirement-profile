<?php

namespace Wirement\Profile\Livewire\ApiTokens;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Wirement\Profile\WirementProfile;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Laravel\Sanctum\PersonalAccessToken;

class ManageApiTokens extends BaseLivewireComponent implements HasTable
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->authUser()->tokens()->latest())
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('name'),
                ]),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('updateToken')
                    ->label(__('wirement-profile::default.action.update_token.label'))
                    ->modalHeading(__('wirement-profile::default.action.update_token.title'))
                    ->modalWidth('lg')
                    ->modalCancelAction(false)
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->modalSubmitActionLabel(__('wirement-profile::default.action.update_token.modal.label'))
                    ->schema(fn (PersonalAccessToken $record, Schema $schema) => $schema->schema(fn () => collect(WirementProfile::plugin()?->getApiTokenPermissions())
                        ->map(fn ($permission) => Checkbox::make($permission)->label(__($permission))->default($record->can($permission)))
                        ->toArray())
                        ->columns())
                    ->action(fn ($record, array $data) => $this->updateToken($record, $data)),
                Action::make('deleteToken')
                    ->color('danger')
                    ->modalWidth('md')
                    ->label(__('wirement-profile::default.action.delete_token.label'))
                    ->modalHeading(__('wirement-profile::default.action.delete_token.title'))
                    ->modalDescription(__('wirement-profile::default.action.delete_token.description'))
                    ->action(fn ($record) => $this->deleteToken($record)),
            ]);
    }

    public function updateToken(PersonalAccessToken $record, array $data)
    {
        $record->forceFill([
            'abilities' => WirementProfile::plugin()?->validPermissions(array_keys(array_filter($data))),
        ])->save();

        $this->sendNotification();
    }

    public function deleteToken(PersonalAccessToken $record)
    {
        $record->delete();

        $this->sendNotification(__('wirement-profile::default.notification.token_deleted.success'));
    }

    public function render()
    {
        return view('wirement-profile::livewire.api-tokens.manage-api-tokens');
    }
}
