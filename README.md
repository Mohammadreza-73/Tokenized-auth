# Tokenized-auth

[![Latest Stable Version](http://poser.pugx.org/m.rahimi/laravel-tokenize-auth/v)](https://packagist.org/packages/m.rahimi/laravel-tokenize-auth))
[![Total Downloads](http://poser.pugx.org/m.rahimi/laravel-tokenize-auth/downloads)](https://packagist.org/packages/m.rahimi/laravel-tokenize-auth)
[![License](http://poser.pugx.org/m.rahimi/laravel-tokenize-auth/license)](https://packagist.org/packages/m.rahimi/laravel-tokenize-auth)

This package creates a 6 digits token, which allows you to send it by SMS, email, etc to users and they can login into their account with that token by sending it to specific endpoint.

Tokens are auto expiring and single use.
# Installation
```
composer require m.rahimi/laravel-tokenize-auth
```

Then publish the config file:
```
php artisan vendor:publish
```

# Basic usage:
Basically, this package introduces 2 endpoints, which you can send requests to them.

1. Generate and send the token to the user
```php
POST '/tokenized-login/request-token?email=foo@example.com'
```

2. Accepts the token and authoenticates the user if the token was valid.
```php
POST '/tokenized-login/login?email=foo@example.com'
```
Note: If you want to use another shape of urls, just disable default routes in config file ```'use_default_routes' => false,``` and define your routes.

# Customization:
You are free to customize and swap the default classes, with your own classes.

If you want to swap the default implementations behind the facades with your own, you can do it within the `boot` method of any service provider class like this :

```php
    /**
     * The life time of tokens in seconds.
     */
    'token_ttl' => 120,

    /**
     * Determine if you are ok with using the routes
     * defined within the package or you want to define them
     */
    'use_default_routes' => true,

    /**
     * The rules to validate the the receiver address.
     * Whether email or phone number.
     */
    'address_validation_rules' => ['required', 'email'],

    /**
     * Specify the middlewares to be applied on
     * the routes, which the package has provided for you.
     */
    'route_middlewares' => ['api'],

    /**
     * Define a prefix for the urls to avoid conflicts.
     * Note: the prefix should NOT end in a slash / character.
     */
    'route_prefix_url' => '/tokenized-login',

    /**
     * Notification class used to send the token.
     * You may define your own token sender class.
     */
    'token_sender' => \TokenizedLogin\TokenSenders\TokenSender::class,

    /**
     * You can change the way you generate the token by define you own class.
     */
    'token_generator' => \TokenizedLogin\TokenGenerators\TokenGenerator::class,

    /**
     * You can extend Responses class and override
     * it's methods, to define your own responses.
     */
    'responses' => \TokenizedLogin\Http\Responses\Responses::class,

    /**
     * You can change the way you fetch the user from your database
     * by defining a custom user provider class, and set it here.
     */
    'user_provider' => \TokenizedLogin\UserProvider::class,

    /**
     * You may provide a middleware throttler to throttle
     * the requesting and submission of the tokens.
     */
    'throttler_middleware' => 'throttle:3,1',
];
```

--------------------

### :raising_hand: Contributing 
If you find an issue, or have a better way to do something, feel free to open an issue or a pull request.