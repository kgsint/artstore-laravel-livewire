# Art supplies store with TALL stack 

## Features
- Authentication
- Admin Panel with [FilamentPHP](https://filamentphp.com/) (Inventory Management, Order Managment, Stock Managment, etc)
- Cart Implementation
- Payment with [Stripe](https://stripe.com)

## Installation and Setup

```bash
composer install
```
```bash
npm install
```
copy `.env.example` file to `.env` :
```
cp .env.example .env
```
Generate `APP_KEY`
```bash
php artisan key:generate
```

[Create new Filament](https://filamentphp.com/docs/3.x/panels/installation#create-a-user) user using 
```bash
php artisan make:filament-user
```
or authorize with permission (https://filamentphp.com/docs/3.x/panels/users#authorizing-access-to-the-panel)
```php
<?php
 
namespace App\Models;
 
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
 
class User extends Authenticatable implements FilamentUser
{
    // ...
 
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }
}
```
