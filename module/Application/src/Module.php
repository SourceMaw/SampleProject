<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\AdminController;
use Application\Controller\LoginController;
use Application\Controller\LogOutController;
use Laminas\ModuleManager\ModuleManager;

class Module
{
    const VERSION = '3.1.3';

    public function init(ModuleManager $manager){

        $events = $manager->getEventManager();
        $sharedEvents = $events->getSharedManager();

        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {



            $adminControllers = [
                LoginController::class,
                AdminController::class,
                LogOutController::class


            ];

            $requireLoginControllers = array_merge($adminControllers);



            $controller = $e->getTarget();
            if (in_array(get_class($controller), $requireLoginControllers)) {


                $controller->layout('layout/layout');
            }



            $userSession = \App\Auth::getAuth();

            if(empty($userSession) && in_array(get_class($controller),$requireLoginControllers)){


            }


            if(!empty($userSession['user_type']) && $userSession['user_type'] == \Models\User::TYPE_ADMIN && !in_array(get_class($controller), $adminControllers)){


                header('Location: /admin');
                exit;
            }


        }, 100);

    }


    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
