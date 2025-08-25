# Wirement Profile — A Filament v4 Profile Management Package

A modern Filament v4 package for profile management with built-in two-factor authentication, passkey support, and team management for Filament panels.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wirement/wirement-profile.svg?style=flat-square)](https://packagist.org/packages/wirement/wirement-profile)
[![Total Downloads](https://img.shields.io/packagist/dt/wirement/wirement-profile.svg?style=flat-square)](https://packagist.org/packages/wirement/wirement-profile)

Wirement Profile provides a complete profile management solution with advanced security features, all implemented with **native Filament panels and components** following Wirement design standards.

**Features:**
- 🔐 **Two-Factor Authentication** - Built-in Google 2FA with QR codes and recovery codes
- 🔑 **Passkey Authentication** - Modern passwordless authentication using WebAuthn
- 👤 **Profile Management** - Complete user profile with photo uploads
- 👥 **Team Management** - Optional team collaboration features
- 🔗 **API Token Management** - Secure API access tokens
- 🎨 **Modern UI** - Beautiful, Apple-inspired design following Wirement standards

## Requirements

- **PHP 8.2+**
- **Laravel v11.28+** 
- **Filament v4.0+**
- **Tailwind CSS v4.0+** (if using custom themes)

## Installation

You can install the package via composer:

```bash
composer require wirement/wirement-profile
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="wirement-profile-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="wirement-profile-config"
```

Optionally, you can publish the views using:

```bash
php artisan vendor:publish --tag="wirement-profile-views"
```

Or use the install command to set everything up:

```bash
php artisan wirement-profile:install
```

### Tailwind CSS v4 Setup

This package uses Tailwind CSS v4 with its native configuration approach (no config file needed):

1. Install Tailwind CSS v4:
```bash
npm install tailwindcss@next
```

2. Import the package styles in your main CSS file:
```css
@import "tailwindcss";
@import "../vendor/wirement/wirement-profile/resources/css/app.css";
```

3. Build your assets:
```bash
npx tailwindcss -i resources/css/app.css -o public/css/app.css
```

Or add to your `package.json` scripts:
```json
{
  "scripts": {
    "build": "tailwindcss -i resources/css/app.css -o public/css/app.css",
    "dev": "tailwindcss -i resources/css/app.css -o public/css/app.css --watch"
  }
}
```

## Setup

### 1. Update your User model

Add the `HasTwoFactorAuthentication` trait to your User model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Wirement\Profile\Traits\HasTwoFactorAuthentication;

class User extends Authenticatable
{
    use HasTwoFactorAuthentication;
    
    // ... rest of your model
}
```

### 2. Add the plugin to your Filament panel

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Wirement\Profile\WirementProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                WirementProfilePlugin::make()
                    ->enableTwoFactorAuthentication()
                    ->enablePasskeyAuthentication()
                    ->addTwoFactorMenuItem()
                    ->forceTwoFactorSetup(false), // Set to true to force 2FA setup
            ]);
    }
}
```

### 3. Run migrations

```bash
php artisan migrate
```

## Configuration

The package publishes a configuration file to `config/wirement-profile.php` where you can customize:

- Two-factor authentication settings
- Passkey configuration
- Profile features (photos, API tokens, teams, etc.)
- UI theme and layout settings

```php
return [
    'enable_two_factor_authentication' => true,
    
    'two_factor' => [
        'google_2fa' => ['enabled' => true],
        'passkey' => ['enabled' => true],
        'show_menu_item' => true,
        'force_setup' => false,
    ],
    
    'features' => [
        'profile_photos' => true,
        'api_tokens' => true,
        'teams' => false,
        'account_deletion' => true,
    ],
    
    'ui' => [
        'theme' => [
            'primary_color' => 'indigo',
        ],
    ],
];
```

## Usage

### Two-Factor Authentication

Users can enable 2FA from their profile page. The package provides:

- **Google Authenticator** integration with QR codes
- **Recovery codes** for backup access
- **Passkey support** for modern passwordless authentication

### Accessing Plugin Features

You can access plugin features programmatically:

```php
use Wirement\Profile\WirementProfile;

// Get the plugin instance
$plugin = WirementProfile::plugin();

// Check if features are enabled
if ($plugin->hasTeamsFeatures()) {
    // Team functionality is available
}

if ($plugin->hasTwoFactorAuthenticationFeatures()) {
    // 2FA functionality is available
}
```

### Custom Profile Components

You can add the 2FA components to any custom profile page:

```blade
<x-filament-panels::page>
    @livewire(\Wirement\Profile\TwoFactor\Livewire\TwoFactorAuthentication::class)
    @livewire(\Wirement\Profile\TwoFactor\Livewire\PasskeyAuthentication::class)
</x-filament-panels::page>
```

### Middleware

The package includes middleware for:

- **TwoFactorChallenge** - Prompts for 2FA verification
- **ForceTwoFactorSetup** - Requires users to set up 2FA

## Design Philosophy

Wirement Profile follows modern design principles:

- **Clean, Apple-inspired aesthetics** with subtle gradients and shadows
- **Consistent spacing** and visual rhythm
- **Large touch targets** for better usability
- **Neutral color palette** with elegant accent colors
- **Typography** using Inter or SF Pro fonts

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Knut W. Horne](https://kwhorne.com) - Writer, Creator, Developer
- [taylorotwell](https://github.com/taylorotwell) - Laravel framework
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
