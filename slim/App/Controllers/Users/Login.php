<?php
namespace App\Controllers\Users;
use App\Models\User as User;
use Slim\Collection;
use OAuth2 as OAuth2;

class Login  {

    const SUBJECT_IDENTIFIER = 'username';
    private $appConfig;

    public function __construct( $appConfig)
    {
        $this->appConfig = $appConfig;
    }
    
    public function varifyUser($request, $response, $args) {
        $data = $request->getParsedBody();
        $user = $this->validateUser($data['email'],$data['password']);
        if($user)
        {
            $db = $this->appConfig['settings']['odb'];
            OAuth2\Autoloader::register();
            $storage = new OAuth2\Storage\Pdo(array('dsn' => "mysql:dbname=".$db['dbname'].";host=".$db['host'], 'username' => $db['user'], 'password' => $db['pass']));
            $server = new OAuth2\Server($storage);
            $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
            $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
            $tObj = $server->handleTokenRequest(OAuth2\Request::createFromGlobals(),NULL,$user);
            $tObj->send();   
        }
        else
        {
            return $response->withJson(['sucess'=> false,'msg'=>'Login failed. Incorrect credentials']);
        }
    }
 
    public function validateUser($email,$password)
    {    
        $result = false;
        $userData = User::where('email', '=', $email)->first();
        if($userData){
            if(validate_password($password,$userData->password) == 1){
                $result = $userData->id;
            }
        }
        return $result;

    }

    public function verifyToken($request, $response, $args){
        $db = $this->appConfig['settings']['odb'];
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
    }

    public function test($request, $response, $args){
        echo "sdfsdfsdfsdfdsfsdfdsf";
    }
}


/* 
 * Password Hashing With PBKDF2 (http://crackstation.net/hashing-security.htm).
 * Copyright (c) 2013, Taylor Hornby
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, 
 * this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation 
 * and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 */
// These constants may be changed without breaking existing hashes.
define("PBKDF2_HASH_ALGORITHM", "sha1");
define("PBKDF2_ITERATIONS", 1000);
define("PBKDF2_SALT_BYTES", 24);
define("PBKDF2_HASH_BYTES", 24);
define("HASH_SECTIONS", 2);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 0);
define("HASH_PBKDF2_INDEX", 1);


function create_hash($password=NULL)
{    
	$salt = base64_encode(openssl_random_pseudo_bytes(PBKDF2_SALT_BYTES));
    return $salt . ":" .
        base64_encode(pbkdf2(
            PBKDF2_HASH_ALGORITHM,
            $password,
            base64_decode($salt),
            PBKDF2_ITERATIONS,
            PBKDF2_HASH_BYTES,
            true
        ));
}
function validate_password($password=NULL, $good_hash=NULL)
{
    $params = explode(":", $good_hash);
    if(count($params) < HASH_SECTIONS)
       return false;
    $pbkdf2 = base64_decode($params[HASH_PBKDF2_INDEX]);
    return slow_equals(
        $pbkdf2,
        pbkdf2(
           PBKDF2_HASH_ALGORITHM,
            $password,
            base64_decode($params[HASH_SALT_INDEX]),
            1000,
            strlen($pbkdf2),
            true
        )
    );
}
// Compares two strings $a and $b in length-constant time.
function slow_equals($a=NULL, $b=NULL)
{
    $diff = strlen($a) ^ strlen($b);
    for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
    {
        $diff |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $diff === 0;
}
/*
 * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
 * $algorithm - The hash algorithm to use. Recommended: SHA256
 * $password - The password.
 * $salt - A salt that is unique to the password.
 * $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
 * $key_length - The length of the derived key in bytes.
 * $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
 * Returns: A $key_length-byte key derived from the password and salt.
 *
 * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
 *
 * This implementation of PBKDF2 was originally created by https://defuse.ca
 * With improvements by http://www.variations-of-shadow.com
 */
function pbkdf2($algorithm=NULL, $password=NULL, $salt=NULL, $count=NULL, $key_length=NULL, $raw_output = false)
{
    $algorithm = mb_strtolower($algorithm);
    if(!in_array($algorithm, hash_algos(), true))
        trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
    if($count <= 0 || $key_length <= 0)
        trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
    if (function_exists("hash_pbkdf2")) {
        // The output length is in NIBBLES (4-bits) if $raw_output is false!
        if (!$raw_output) {
            $key_length = $key_length * 2;
        }
        return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
    }
    $hash_length = strlen(hash($algorithm, "", true));
    $block_count = ceil($key_length / $hash_length);
    $output = "";
    for($i = 1; $i <= $block_count; $i++) {
        // $i encoded as 4 bytes, big endian.
        $last = $salt . pack("N", $i);
        // first iteration
        $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
        // perform the other $count - 1 iterations
        for ($j = 1; $j < $count; $j++) {
            $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
        }
        $output .= $xorsum;
    }
    if($raw_output)
        return substr($output, 0, $key_length);
    else
        return bin2hex(substr($output, 0, $key_length));
}
function createCryptServiceObject($index = 0){
	global $tmpSrvIpArr,$encryptObj,$isCryptService;
	if($isCryptService == false && $index < count($tmpSrvIpArr))
	{
		try{
			$encryptObj = new SoapClient("http://".$tmpSrvIpArr[$index].":8024/FSCC?wsdl",array('trace' => true));
			$isCryptService = true;
		}
		catch (Exception $e){
			$isCryptService = false;
			createCryptServiceObject($index+1);
		}
	}
}

