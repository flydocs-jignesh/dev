<?php
use Slim\Http\Request;
use Slim\Http\Response;

$mw = function ($request, $response, $next){
	$server = new OAuth2\Server($this->oauth);
	// Add the "Client Credentials" grant type (it is the simplest of the grant types)
	$server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->oauth));
	// Add the "Authorization Code" grant type (this is where the oauth magic happens)
	$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->oauth));
	// Handle a request to a resource and authenticate the access token
	if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
		$server->getResponse()->send();
		die;
	}
	$response = $next($request, $response);
	return $response;
};

$app->post('/generateToken',function(Request $request, Response $response){

    // @ generate a fresh token
    // @ Token is valid till 1 hr or 3600 seconds after which it expires
    // @ Token will not be auto refreshed
    // @ generation of a new token should be handled at application level by calling this api

    // @ add parameter : ,['access_lifetime'=>3600] if you want to extent token life time from default 3600 seconds
    //print_r($this->oauth);
    $server = new OAuth2\Server($this->oauth);
    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->oauth));
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->oauth));

    // @ generate a Oauth 2.0 token in json with format below
    // @ {"access_token":"ac7aeb0ee432bf9b73f78985c66a1ad878593530","expires_in":3600,"token_type":"Bearer","scope":null}
    $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();

});


$app->get('/', 'Login:index')->add($mw);
