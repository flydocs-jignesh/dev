<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
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

$app->group('/api',
    function () {

        $jwtMiddleware = $this->getContainer()->get('jwt');
        $optionalAuth = $this->getContainer()->get('optionalAuth');
        /** @var \Slim\App $this */

        $this->get('/', 'Login:index');
        $this->post('/login/varifyUser','Login:varifyUser');

        $this->get('/fleet','AircraftController:getAircaftList');
        $this->get('/fleet/[{id}]','AircraftController:getAircaft');

        $this->get('/MCC','MCCController:getWorkPackList');
        $this->get('/MCC/[{id}]','MCCController:getWorkPack');
        $this->post('/MCC','MCCController:CreateWorkPack');
});
