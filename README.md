<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel + Vue Starter Kit

[Laravel 12.x Inertia Vue](https://laravel.com/docs/12.x)

##### Libraries
- [Laravel Breeze And Inertia Vue](https://laravel.com/docs/12.x/starter-kits#breeze-and-inertia)
- [Axios 1.10.0](https://axios-http.com/docs/intro)
- [Pinia 0.11.1](https://vuejs.org/guide/quick-start)

##### Requirements
- [PHP >= 8.2](https://laravel.com/docs/12.x/deployment)
- [Node >= 18.3](https://laravel.com/docs/12.x/deployment)

##### Installation
    composer install
    copy .env-example to .env
    php artisan key:generate
    php artisan migrate:fresh --seed
    composer run dev

##### Structure
- app
    - Http
    - Models
    - Providers
    - Traits
- bootstrap
- config
- database
    - factories
    - migrations
    - seeders
- public
- resources
    - css
    - js
    - views
- routes
- storage
    - app
    - framework
    - logs
- tests

#### Laravel Optimize Performance (optional)
1. When installing vendors in Laravel, use the --no-dev option so that development dependencies are not installed.
    composer install --optimize-autoloader --no-dev
2. Use artisan optimize
    php artisan optimize

#### Reminder
    app/Http/Middleware/HandleInertiaRequests.php => setting $page
    resouce/js/components/appSidebar.vue => menu sidebar
    resouce/js/layouts/AuthLayout.vue => view auth
    resouce/js/layouts/AppLayout.vue => view dashboard