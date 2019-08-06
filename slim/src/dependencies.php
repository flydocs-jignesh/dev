<?php
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($c)  use($capsule) {
    return $capsule;
};

// Jwt Middleware
$container['jwt'] = function ($c) {
    $jws_settings = $c->get('settings')['jwt'];
    return new \Slim\Middleware\JwtAuthentication($jws_settings);
};

// Optional Auth Middleware
$container['optionalAuth'] = function ($c) {
  return new App\Middleware\OptionalAuth($c);
};

$container['Login'] = function ($c) {
    return new App\Controllers\Users\Login($c);
};
$container['AircraftController'] = function ($c) {
    return new App\Controllers\Components\AircraftController($c);
};


