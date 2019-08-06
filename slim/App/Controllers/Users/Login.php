<?php
namespace App\Controllers\Users;
use App\Models\User as User;
use Firebase\JWT\JWT;
use Slim\Collection;
use DateTime;

class Login  {

    const SUBJECT_IDENTIFIER = 'username';
    private $appConfig;

    public function __construct( $appConfig)
    {
        $this->appConfig = $appConfig;
    }
    public function index($request, $response, $args) {
        echo (User::first()->username);
        echo "HEre";
    }
    public function varifyUser($request, $response, $args)
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