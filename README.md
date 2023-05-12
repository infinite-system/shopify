<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Shopify Intergation with Laravel

# Setup the application

Git clone the repository:

```bash
git clone https://github.com/infinite-system/shopify.git
```

Install laravel valet:
https://laravel.com/docs/10.x/valet

Run this command in root dir to setup http://shopify.test
local environment.
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


Products public unathorized API can be found at:
http://34.71.79.49/shopify/api/products via GET request or simply going to the link.

Products authorized add API can be found at:
http://34.71.79.49/shopify/api/products via POST request
via POSTMAN or any other API checker, if viewed through browser it will redirect to login page.
It can also be access by loginning into the application and submitting the requests there.


Benchmarking can be found at:
http://34.71.79.49/shopify/clockwork/app#

OpenAPI Specs can be found here:
https://github.com/infinite-system/shopify/tree/main/specs/specs.yaml

## About
The API was created using Orion Laravel extension.


I've enjoyed working on this task, the main hurdle for me was not the building of application logic but going through the intense documentation of Shopify, that's why I could not get to the hooks implementation, but I've implemented adding a product and an image, as well as editing the product and an image, and it syncs with the Shopify store.
The only parameter that does not sync with the store is the quantity because that requires Inventory API from shopify, in my example I implemented Product, Product Variant and Image APIs:


Notable files to make it work are:

### ProductsAPI Controller:
https://github.com/infinite-system/shopify/blob/main/app/Http/Controllers/Api/ProductApiController.php

### ProductsAPI Policy for Add/Edit Product Auth:
https://github.com/infinite-system/shopify/blob/main/app/Policies/ProductPolicy.php

### The validations are implemented in
https://github.com/infinite-system/shopify/blob/main/app/Http/Requests/ProductRequest.php

### Shopify Service Provider and config are here: 

https://github.com/infinite-system/shopify/blob/main/app/Providers/ShopifyServiceProvider.php

https://github.com/infinite-system/shopify/blob/main/config/shopify.php

https://github.com/infinite-system/shopify/blob/main/app/Http/Api/Shopify.php

### The product blades are here:

https://github.com/infinite-system/shopify/tree/main/resources/views/products

### Other notes

#### APIs
I've worked and built full scale APIs of my own before for several companies, one of them is Flexible Payments API that runs at https://officestock.com which integrates Stripe and Square APIs into a single interface, with the ability to expand to more APIs.

I've also worked extensively with authentication and have actually built my own token authentication systems from scratch before Laravel in the project called Infinite System.

#### Authentication

I've also now implemented not just one but several ways of Laravel Authentication, I have an SPA where I've implemented XSRF token authentication.

In this app I implemented token based authentication via Sanctum, once you login you can modify a token that is submitted and see the api fail, it will only work with a correct token supplied by Sanctum.

The only auth that I have not tried extensively yet, is Laravel Passport.

Anyway, this is a big topic, but just wanted to let you know that I enjoyed this task and I am passionate about building these kinds of systems, APIs, AUTH and enjoy building, debugging, improving and polishing for high performance and maintainability.

I am also well versed in frontend, VueJS, React, Tailwind and all that goodness.
Would love to join the team who share the same passion.


Regards,

Evgeny Kalashnikov