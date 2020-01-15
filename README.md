# Rest API Project Monitoring with Laravel and MongoDB

## Description
Have 3 roles user : Superuser (```super```), Project Manager (```pm```) and Programmer (```programmer```).

## Requirement
Install ```mongodb``` on your system (``` apt-get ``` or the like) and don't try to install with ```pecl```:
```bash
sudo pecl install mongodb
```

## Installation

Install package with ```composer``` :

```bash
composer install
```

Migrate Table or Collections:
```bash
php artisan migrate
```

Add dummy data with seeder:
```bash
php artisan db:seed
```

For reset data :
```bash
php artisan migrate:refresh --seed
```