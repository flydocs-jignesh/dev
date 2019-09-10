<?php
namespace App\Controllers\Users;
use App\Models\User as User;
use Firebase\JWT\JWT;
use Slim\Collection;
use DateTime;
use OAuth2 as OAuth2;

class Login  {

    const SUBJECT_IDENTIFIER = 'username';
    private $appConfig;

    public function __construct( $appConfig)
    {
        $this->appConfig = $appConfig;
    }
    public function varifyUser($request, $response, $args) {
		
		$db = $this->appConfig['settings']['odb'];
		//print_r($db);exit;
		OAuth2\Autoloader::register();
		$storage = new OAuth2\Storage\Pdo(array('dsn' => "mysql:dbname=".$db['dbname'].";host=".$db['host'], 'username' => $db['user'], 'password' => $db['pass']));
        $server = new OAuth2\Server($storage);
		
		if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
			$server->getResponse()->send();
			die;
		}
		else{
			$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
			echo "sucess=".$token['user_id']."Exp=".date("Y-m-d H:i:s",$token['expires']);
		}
		exit;
		$db = $this->appConfig['settings']['odb'];
		//print_r($db);exit;
		OAuth2\Autoloader::register();
		$storage = new OAuth2\Storage\Pdo(array('dsn' => "mysql:dbname=".$db['dbname'].";host=".$db['host'], 'username' => $db['user'], 'password' => $db['pass']));
        $server = new OAuth2\Server($storage);
		$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
		$tObj = $server->handleTokenRequest(OAuth2\Request::createFromGlobals(),NULL,1234);
		
		//$serverRequest = OAuth2\Request::createFromGlobals();
        //$serverResponse = new OAuth2\Response();
		//$server->handleAuthorizeRequest($serverRequest, $tObj, true, 1234);
		
		$tObj->send();
		
		//$server->handleAuthorizeRequest(NULL, NULL, true, 1234);
		//$server->handleAuthorizeRequest(OAuth2\Request, OAuth2\Responce, true, 2);
        //echo $response->getHttpHeader('Location');
    }
    public function varifyUser_old($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $user = $this->UserNameExist($data['username']);           
        if($user)
        {
            $varifyPassword = $this->varifyPassword($data['password']);  
            $user->token = $this->generateToken($user); 
            if($varifyPassword)
            {
                return $response->withJson(['sucess'=> true,'msg'=>'logged in susessfully','user' => $user]);     
            }else
            {
                return $response->withJson(['sucess'=> false,'msg'=>'Login failed. Incorrect credentials']);    
            }        
        }
        else
        {
            return $response->withJson(['sucess'=> false,'msg'=>'Login failed. Incorrect credentials']);
        }
    }
    
    public function UserNameExist($userName)
    {    
        return $count = User::where('username', '=', $userName)->first();
    }

    public function varifyPassword($password)
    {    
       // echo $this->encrypt_password($password);
        return $user = User::where('password', '=', $password)->first();
    }
    
    public function generateToken(User $user)
    {
        $now = new DateTime();
        $future = new DateTime("now +2 hours");

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(random_bytes(16)),
            'iss' => $this->appConfig['settings']['app']['url'],  // Issuer
            "sub" => $user->{self::SUBJECT_IDENTIFIER},
        ];

        $secret = $this->appConfig['settings']['jwt']['secret'];
        $token = JWT::encode($payload, $secret, "HS256");
        return $token;
    }
}