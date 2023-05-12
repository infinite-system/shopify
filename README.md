<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Shopify Intergation with Laravel

# Setup the application

Git clone the repository:

```bash
git clone https://github.com/infinite-system/shopify.git
```

Install laravel valet.
https://laravel.com/docs/10.x/valet

```
valet link shopify
```

Run composer install:
```bash
composer install
```

Install laravel jetstream in livewire mode:
```bash
php artisan jetstream:install livewire
```

Run the migrations:
```bash
php artisan migrate
```

Install mysql with 'shopify' database and modify env file to match credentials:
```
Modify .env file to have proper mysql password
```

Live example of the application can be found @:
http://34.71.79.49/shopify

Register an account there and login.
There you will be able to add and edit products, that will sync with Shopify store.

The store is located at https://realized-one.myshopify.com/

The password to the test store is `1234`


Products open API can be found at:
http://34.71.79.49/shopify/api/products via POSTMAN or any other API checker, if viewed through browser it will redirect to login page.

Benchmarking can be found at:
http://34.71.79.49/shopify/clockwork/app#

OpenAPI Specs can be found here:
https://github.com/infinite-system/shopify/tree/main/specs/specs.yaml


