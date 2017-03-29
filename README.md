laravel-health-checks
=====================

Allows your Laravel app to run health checks on itself

Usage
=====

* Add to Composer:
    - Repositories: `{ "type": "vcs", "url": "git@github.com:npmweb/laravel-health-check" },`
    - Dependencies: `"npmweb/laravel-health-check": "dev-master@dev",`
    - `composer update`
* Add service provider:

app.php

    'providers' => array(
    	...
    	'NpmWeb\LaravelHealthCheck\LaravelHealthCheckServiceProvider',
    );
    
* Add route for the health check controller:

routes.php

    Route::resource(
        'monitor/health',
        'NpmWeb\LaravelHealthCheck\Controllers\HealthCheckController',
        ['only' => ['index','show']]
    );
    
* Configure the health checks:
    - `php artisan config:publish npmweb/laravel-health-check`
    - Edit `app/config/packages/npmweb/laravel-health-check/config.php`

For information on each health check, see comments in the appropriate class under src/NpmWeb/LaravelHealthCheck/Checks.