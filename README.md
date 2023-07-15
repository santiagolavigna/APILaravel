#API REST ecommerce Laravel 9 - MySQL 8.0

JWT
    package:
        composer require php-open-source-saver/jwt-auth
    package public configuration:
        php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
    secret keys generated
        php artisan jwt:secret
    Changed config/auth.php,User model, Auth controller