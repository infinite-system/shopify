<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Shopify Products API Integration with Laravel 10.10

![project](https://github.com/infinite-system/shopify/assets/150185/a2f74974-6ef6-4a62-8801-91782baeb9a1)
![project2](https://github.com/infinite-system/shopify/assets/150185/5dfba9eb-4caf-47ed-a677-cbb2131a7aa4)
![project3](https://github.com/infinite-system/shopify/assets/150185/8459a329-4852-4c44-858a-e7864b658517)

# Setup the Application

1. Git clone the repository:
```bash
git clone https://github.com/infinite-system/shopify.git
```

2. Copy the .env file that I sent you into the root dir

3. Install MySQL/MariaDB with 'shopify' database and modify .env file to match credentials, I personally use Docker for this.

4. Run composer install:
```bash
composer install
```
5. Run the `install/install.sh` script that will install Laravel Jetstream & Run migrations correctly:
```bash
sh install/install.sh
```

6. Install Laravel Valet & run this command in app root dir to setup
http://shopify.test local environment.

```
valet link shopify
```

Live example of the application can be found here:
https://34.71.79.49/shopify

Register an account there and login.
There you will be able to add and edit products, that will sync with Shopify store.
You can test password reset there as well.

The Shopify store is located at https://realized-one.myshopify.com/
The password to the test store is `1234`

Products public unathorized API can be found at:
https://34.71.79.49/shopify/api/products via GET request or simply going to the link.

Products authorized add API can be found at:
https://34.71.79.49/shopify/api/products via POST request
via POSTMAN or any other API checker by supplying the proper bearer token, if viewed through browser it will redirect to login page.

You can use this token Authorization: Bearer 2|ZhNjguG7J2E576Ew95F1tfnegzy8lJ20rO3Kct7O
in POSTMAN to submit requests to the api that are under these routes.
```bash
GET|HEAD        api/products ................ api.products.index ›  Api\ProductApiController@index
POST            api/products ................. api.products.store › Api\ProductApiController@store
GET|HEAD        api/products/{product} ......... api.products.show › Api\ProductApiController@show
PUT|PATCH       api/products/{product} ..... api.products.update › Api\ProductApiController@update
DELETE          api/products/{product} ... api.products.destroy › Api\ProductApiController@destroy
```


API Route definitions can be found here:
https://github.com/infinite-system/shopify/blob/main/routes/api.php

It can also be access by login in into the application and submitting the requests there.

Benchmarking can be found at:
https://34.71.79.49/shopify/clockwork/app#

OpenAPI Specs can be found here:
https://github.com/infinite-system/shopify/tree/main/specs/specs.yaml

## About the API
The API was created using Orion Laravel extension.

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

## Webhooks Implementation & SSL

To implement webhooks I needed to install a valid SSL certificate on my server, I used zero-ssl to get that done for free. Using nginx configuration I installed it really quickly.

The main controller that does updating and creating via webhooks can be found here:
https://github.com/infinite-system/shopify/blob/main/app/Http/Controllers/ProductWebhooksController.php

Webhook route endpoints are:
```bash
GET|POST|HEAD   api/shopify-webhooks/products/create .... ProductWebhooksController@create
GET|POST|HEAD   api/shopify-webhooks/products/delete .... ProductWebhooksController@delete
GET|POST|HEAD   api/shopify-webhooks/products/update .... ProductWebhooksController@update
```

I've tried to implement webhook verification for my app, but that turned out to be a very difficult task that seems to be unresolved by shopify. My app is a public app, and the way they have published it only works for the private apps.

So, I've implemented a basic verification instead.

To test webhooks, you can access the test store with these credentials:
```
email:sgtesting2@gmail.com
password:Test1234
```
at https://realized-one.myshopify.com/admin

I've also sent an invite to create an account to Alejandro Morales @ Splice Digital, if this test account fails due to account verification.

## API Performance Report

Currently the API for create / update the products are almost the fastest they can be, because they use the Products API that can update Product Variant API and Image API in one request.
Initially I made it via 3 separate requests, and that was much slower as each request took around 600 milliseconds making it around 2 seconds in speed and longer if image is being uploaded, now it just takes around 600ms.

The optimization that can be done right now is when a product with an image is created, we can make uploading the image through a URL rather than through an attachment like it is being done right now, but it is uploaded via attachment right now, because I made it to work with a local server and local server does not have public urls from which Shopify can download images.

Another optimization that can speed up the API significantly if the create / update / delete API that is made for Laravel is completely decoupled from the Shopify API, then when an create / update / delete is hit, it only deletes from the local database and creates a job in the queue that will be processed a bit later, this way the API responsiveness will be really fast, as it only interfaces with our server, the user does not have to wait for Shopify to respond, this way it can be made much faster, but it will be a bit out of sync, as jobs in queue can take time to process, but the advantage of queues is that the task can be re-run via cron job multiple times to sync with the Shopify servers, so it has it's downsides but also benefits, in my opinion it is too much to implement jobs for this task at this scale.

You can test the performance of API if you login into the application and start adding / updating items and check the https://34.71.79.49/shopify/clockwork/app url.

## Other notes

### APIs
I've worked and built full scale APIs of my own before for several companies, one of them is Flexible Payments API that runs at https://officestock.com which integrates Stripe and Square APIs into a single interface, with the ability to expand to more APIs.

I've also worked extensively with authentication and have actually built my own token authentication systems from scratch before Laravel in the project called Infinite System.

### Authentication

I've also now implemented not just one but several ways of Laravel Authentication, I have an SPA where I've implemented XSRF token authentication.

In this app I implemented token based authentication via Sanctum, once you login you can modify a token that is submitted and see the api fail, it will only work with a correct token supplied by Sanctum.

The only auth that I have not tried extensively yet, is Laravel Passport.

Anyway, this is a big topic, but just wanted to let you know that I enjoyed this task and I am passionate about building these kinds of systems, APIs, AUTH and enjoy building, debugging, improving and polishing for high performance and maintainability.

I am also well versed in frontend, VueJS, React, Tailwind and all that goodness.
Would love to join the team who share the same passion.



Regards,

Evgeny Kalashnikov
