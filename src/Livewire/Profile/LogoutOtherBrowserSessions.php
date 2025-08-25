<?php

namespace Wirement\Profile\Livewire\Profile;

use Filament\Actions\Action;
use Filament\Forms;
use Wirement\Profile\Agent;
use Wirement\Profile\Livewire\BaseLivewireComponent;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogoutOtherBrowserSessions extends BaseLivewireComponent
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('wirement-profile::default.browser_sessions.section.title'))
                    ->description(__('wirement-profile::default.browser_sessions.section.description'))
                    ->aside()
                    ->schema([
                        Forms\Components\ViewField::make('browserSessions')
                            ->hiddenLabel()
                            ->view('wirement-profile::components.browser-sessions')
                            ->viewData(['sessions' => self::browserSessions()]),
                        Actions::make([
                            Action::make('deleteBrowserSessions')
                                ->label(__('wirement-profile::default.action.log_out_other_browsers.label'))
                                ->requiresConfirmation()
                                ->modalHeading(__('wirement-profile::default.action.log_out_other_browsers.title'))
                                ->modalDescription(
                                    __('wirement-profile::default.action.log_out_other_browsers.description')
                                )
                                ->modalSubmitActionLabel(
                                    __('wirement-profile::default.action.log_out_other_browsers.label')
                                )
                                ->modalCancelAction(false)
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label(__('wirement-profile::default.form.password.label'))
                                        ->required()
                                        ->currentPassword(),
                                ])
                                ->action(
                                    fn (array $data) => $this->logoutOtherBrowserSessions($data['password'])
                                ),
                        ]),
                    ]),
            ]);
    }

    public function logoutOtherBrowserSessions(string $password): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        auth(filament()->getAuthGuard())->logoutOtherDevices($password);

        DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', filament()->auth()->user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();

        request()
            ->session()
            ->put([
                'password_hash_' . Auth::getDefaultDriver() => filament()->auth()->user()->getAuthPassword(),
            ]);

        Notification::make()
            ->success()
            ->title(__('wirement-profile::default.notification.logged_out_other_sessions.success.message'))
            ->send();
    }

    /**
     * Get the current sessions.
     */
    public static function browserSessions(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', filament()->auth()->user()->getAuthIdentifier())
            ->orderBy('last_activity', 'desc')
            ->get()->map(function ($session) {
                return (object) [
                    'agent' => tap(new Agent, fn ($agent) => $session->user_agent),
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === request()->session()->getId(),
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });
    }

    public function render(): View
    {
        return view('wirement-profile::livewire.profile.logout-other-browser-sessions');
    }
}
