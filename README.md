<p align="center">
    <a href="https://aimeos.org/">
        <img src="storage/app/public/images/system/logo-system.png" alt="Hallpos logo" title="Hallpos" height="180" />
    </a>
</p>

[Hallpos](https://hallpos.com.co): Agiliza tus ventas, controla tu inventario y obtén informes al instante donde te encuentres. Nuestro sistema POS es fácil de usar y conectar con servicios de facturación electrónica.

![dashboard](./public/dashboard.png)

Visit [demo](https://test.hallpos.com.co/) page:
- User: admin@gmail.com
- Password: 12345678

## Table Of Content

- [Installation](#installation)
  - [Requirements](#requirements)
  - [Steps](#steps)
- [Deploy](#deploy)
  - [Requirements](#requirements-1)
  - [Steps](#steps-1)

## Installation

### Requirements

| Technology   | Version   |
|--------------|-----------|
| <img src="https://www.php.net//images/logos/new-php-logo.svg" width="100" style="margin-top:10px"> <p align="center">php</p> | >= 8     |
| <img src="https://getcomposer.org/img/logo-composer-transparent3.png" width="80" style="margin-left:10px;margin-top:10px"> <p align="center">Composer</p> | >= 2 (optional)    |

### Steps

```bash
git clone git@github.com:Halltec/pos-laravel-v2.git
```

To install the composer dependencies, execute this command:

```bash
cd pos-laravel-v2

composer install
# or install from composer.phar file:
php composer.phar install
```

Create .env file, execute this command:

```bash
cp .env-example .env
```

Set database credentials to the following environment variables:

```env
DB_DATABASE=pos-laravel-v2
DB_USERNAME=example
DB_PASSWORD=example
```

After, execute this command:

```bash
php artisan key:generate
php artisan migrate
php artisan migrate:fresh --seed
```

## Deploy

### Requirements

| Technology   | Version   |
|--------------|-----------|
| <img src="https://www.php.net//images/logos/new-php-logo.svg" width="100" style="margin-top:10px"> <p align="center">php</p> | >= 8     |
| <img src="https://getcomposer.org/img/logo-composer-transparent3.png" width="80" style="margin-left:10px;margin-top:10px"> <p align="center">Composer</p> | >= 2 (optional)    |

### Steps

Create subdomain to new project:

```bash
example.hallpos.com.co
```

Create database, user and into folder created from subdomain, clone Hallpos repository:

```bash
cd <path_folder_subdomain>
rm default.php
git clone git@github.com:Halltec/pos-laravel-v2.git .
```

To install the composer dependencies, execute this command:

```bash
composer install
# or install from composer.phar file:
php composer.phar install
```

Create .env file, execute this command:

```bash
cp .env-example .env
```

Change the following environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=<subdomain>
```

Set database credentials to the following environment variables:

```env
DB_DATABASE=example
DB_USERNAME=example
DB_PASSWORD=example
```

After, execute this command:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
cp htaccess .htaccess
chmod 775 -R storage/app/public
```
