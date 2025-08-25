<?php

namespace Wirement\Profile\Livewire\Profile;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Wirement\Profile\WirementProfile;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class UpdateProfileInformation extends BaseLivewireComponent
{
    public ?array $data = [];

    public function mount(): void
    {
        $data = $this->authUser()->only(['name', 'email']);

        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('wirement-profile::default.update_profile_information.section.title'))
                    ->aside()
                    ->description(__('wirement-profile::default.update_profile_information.section.description'))
                    ->schema([FileUpload::make('profile_photo_path')
                        ->label(__('wirement-profile::default.form.profile_photo.label'))
                        ->avatar()
                        ->image()
                        ->imageEditor()
                        ->visibility('public')
                        ->directory('profile-photos')
                        ->formatStateUsing(fn () => filament()->auth()->user()?->profile_photo_path)
                        ->disk(fn (): string => WirementProfile::plugin()?->profilePhotoDisk())
                        ->visible(fn (): bool => WirementProfile::plugin()?->managesProfilePhotos()),
                        TextInput::make('name')
                            ->label(__('wirement-profile::default.form.name.label'))
                            ->string()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('email')
                            ->label(__('wirement-profile::default.form.email.label'))
                            ->email()
                            ->required()
                            ->unique(get_class(Filament::auth()->user()), ignorable: $this->authUser()),
                        Actions::make([
                            Action::make('save')
                                ->label(__('wirement-profile::default.action.save.label'))
                                ->submit('updateProfile'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->sendRateLimitedNotification($exception);

            return;
        }

        $data = $this->form->getState();

        $user = $this->authUser();

        $isUpdatingEmail = $data['email'] !== $user->email;

        $isUpdatingPhoto = $data['profile_photo_path'] !== $user->profile_photo_path;

        $user->forceFill(Arr::except($data, ['profile_photo_path']))->save();

        if ($isUpdatingEmail) {
            $user->forceFill(['email_verified_at' => null]);

            $user->sendEmailVerificationNotification();
        }

        if ($isUpdatingPhoto) {
            Arr::get($data, 'profile_photo_path')
                ? $user->updateProfilePhoto($data['profile_photo_path'])
                : $user->deleteProfilePhoto();
        }

        $this->sendNotification();
    }

    public function render()
    {
        return view('wirement-profile::livewire.profile.update-profile-information');
    }
}
