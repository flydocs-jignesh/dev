<?php
use Slim\Http\Request;
use Slim\Http\Response;
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        $uri = $uri->withPath(substr($path, 0, -1));
        if($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        }
        else {
            return $next($request->withUri($uri), $response);
        }
    }
    return $next($request, $response);
});


$app->post('/login', 'Login:varifyUser');

$mw = function ($request, $response, $next) {
    global $container;
    $db = $container['settings']['odb'];
    OAuth2\Autoloader::register();
    $storage = new OAuth2\Storage\Pdo(array('dsn' => "mysql:dbname=".$db['dbname'].";host=".$db['host'], 'username' => $db['user'], 'password' => $db['pass']));
    $server = new OAuth2\Server($storage);
    if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
        $server->getResponse()->send();
        die;
    }
    $response = $next($request, $response);
    return $response;
};

$app->group('/api',
    function () {

        /** @var \Slim\App $this */

        $this->get('/', 'Login:index');
        $this->post('/login/varifyUser','Login:varifyUser');

        $this->get('/fleet','AircraftController:getAircaftList');
        $this->get('/fleet/[{id}]','AircraftController:getAircaft');

        $this->post('/MCC','MCCController:getWorkPackList');
        $this->get('/MCC/[{id}]','MCCController:getWorkPack');
        //$this->post('/MCC','MCCController:CreateWorkPack');

        $this->post('/uploadFiles','FileController:uploadFiles');
})->add($mw);
