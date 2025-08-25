<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Configure two-factor authentication settings for the profile package.
    |
    */
    'enable_two_factor_authentication' => true,

    'two_factor' => [
        /*
         * Google 2FA Configuration
         */
        'google_2fa' => [
            'enabled' => true,
        ],

        /*
         * Passkey Configuration
         */
        'passkey' => [
            'enabled' => true,
        ],

        /*
         * Menu Configuration
         */
        'show_menu_item' => true,
        'menu_label' => '2FA',
        'menu_icon' => 'heroicon-s-key',

        /*
         * Setup Configuration
         */
        'force_setup' => false,
        'require_password_for_setup' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Features
    |--------------------------------------------------------------------------
    |
    | Configure which profile features are enabled.
    |
    */
    'features' => [
        'profile_photos' => true,
        'api_tokens' => true,
        'teams' => false,
        'account_deletion' => true,
        'browser_sessions' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Configuration
    |--------------------------------------------------------------------------
    |
    | Configure profile photo settings.
    |
    */
    'profile_photos' => [
        'disk' => 'public',
        'path' => 'profile-photos',
        'max_size' => 1024 * 2, // 2MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Team Configuration
    |--------------------------------------------------------------------------
    |
    | Configure team management settings.
    |
    */
    'teams' => [
        'invitations' => true,
        'max_members' => null, // null for unlimited
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the user interface settings following Wirement design standards.
    |
    */
    'ui' => [
        'theme' => [
            'primary_color' => 'indigo',
            'secondary_color' => 'gray',
            'success_color' => 'green',
            'warning_color' => 'yellow',
            'danger_color' => 'red',
        ],
        'layout' => [
            'max_width' => '7xl',
            'sidebar_width' => 'w-64',
        ],
    ],
];
