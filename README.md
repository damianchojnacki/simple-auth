# Simple Auth

### Installation

Set up env:
- DB
- MAIL

Install dependencies.
```bash
composer install
yarn
```
Generate app key and migrate
```bash
php artisan key:generate
php artisan migrate
```
Serve
```bash
yarn run dev
php artisan serve
```

### App structure
```
app
├───Http
│   └───Controllers
│           AuthController.php
├───Mail
│       AccountDeleted.php
│       ConfirmEmail.php
│   
└───Models
        User.php
```

### Tests
```
   PASS  Tests\Feature\ViewsTest
  ✓ user can view registration page
  ✓ guest cant view confirm email page

   PASS  Tests\Feature\RegistrationTest
  ✓ user can register
  ✓ user cant register twice at the same email
  ✓ user can confirm email
  ✓ user can delete account
  ✓ guest cant delete user account
```

*Please aware - notification email can be in SPAM folder.*
