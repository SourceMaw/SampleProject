<?php
namespace App;

use Firebase\JWT\JWT;
use Laminas\Http\Header\SetCookie;
use Laminas\Session\Container;

class Auth{

    static $key = 'quickbrownfox1234';

    public static function hashPassword($password){

        return md5($password);
    }

    public static function getKey(){

        return self::$key;
    }

    public function authByJWT($jwt){

        try {
            $jwtInfo = JWT::decode($jwt, self::getKey(), array('HS256'));
        }
        catch(\Exception $e){

            return false;
        }
        if(empty($jwtInfo)){

            return false;
        }
        $serviceUser = new \Models\User();
        $auth = $serviceUser->getByEmailAndPassword($jwtInfo->user_email,$jwtInfo->user_password);

        if(!empty($auth)){

            return self::setAuth($auth);

        }
        return false;

    }

    public static function getSessionContainer(){

        $container = new Container();
        return $container;

    }

    public static function getAuth(){

        $container = self::getSessionContainer();
        if($container->offsetExists('user')){

            return $container->offsetGet('user');
        }

        return [];
    }

    public static function setAuth($user){


        $jwt =  JWT::encode($user,Auth::getKey());


        $container = self::getSessionContainer();
        $container->offsetSet('user', $user);

        return $jwt;

    }

    public static function logout(){

        $container = self::getSessionContainer();
        unset($container->user);

    }

    public static function auth($email, $password){


        $serviceUser = new \Models\User();
        $auth = $serviceUser->getByEmailAndPassword($email, self::hashPassword($password));

        if(!empty($auth)){

            return self::setAuth($auth);

        }
        return false;
    }
}
