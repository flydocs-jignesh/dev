<?php
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($c)  use($capsule) {
    return $capsule;
};

$container['oauth'] = function($c){
	$db = $c['settings']['odb'];
	OAuth2\Autoloader::register();
	$storage = new OAuth2\Storage\Pdo(array('dsn' => "mysql:dbname=".$db['dbname'].";host=".$db['host'], 'username' => $db['user'], 'password' => $db['pass']));
	return $storage;
};

$container['Login'] = function ($c) {
    return new App\Controllers\User\Login($c);
};
