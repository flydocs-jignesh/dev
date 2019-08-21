<?php
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($c)  use($capsule) {
    return $capsule;
};

$container['Login'] = function ($c) {
    return new App\Controllers\Users\Login($c);
};

$container['AircraftController'] = function ($c) {
    return new App\Controllers\fleet\AircraftController($c);
};

$container['MCCController'] = function ($c) {
    return new App\Controllers\MCC\MCCController($c);
};

$container['FileController'] = function ($c) {
    return new App\Controllers\Files\FileController($c);
};

$container['ELK'] = function ($c) {
    $elk_settings = $c->get('settings')['ELK'];   
    $ELK = Elasticsearch\ClientBuilder::create()
                    ->setHosts($elk_settings)
                    ->build();
    return $ELK;
};

$container['upload_directory'] = __DIR__ . '/uploads';

