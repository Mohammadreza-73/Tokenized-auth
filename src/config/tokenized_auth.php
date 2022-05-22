<?php

return [
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