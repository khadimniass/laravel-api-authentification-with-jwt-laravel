`composer require tymon/jwt-auth`

'providers' => [

    ...


    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]

`php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`

- config/jwt.php :
  -     'ttl' => env('JWT_TTL', 60),
to config the time of token

- Generate secret key

`php artisan jwt:secret`

