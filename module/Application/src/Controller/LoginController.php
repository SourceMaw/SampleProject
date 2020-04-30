<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Http\Header\SetCookie;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $userSession = \App\Auth::getAuth();

        if(!empty($userSession)){

            header('Location: /admin');
            exit;
        }


        return $viewModel;
    }

    private function setCookie($name, $cookie, $expires = 30){

        $cookie = new SetCookie('auth', $cookie, time() +$expires); // now + 30 sec
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeader($cookie);
    }

    public function loginAction(){

        if($this->request->isPost()){

            $params = $this->params()->fromPost();

            if(!empty($params['email']) && !empty($params['password'])){

                $auth = \App\Auth::auth($params['email'], $params['password']);

                if(!empty($auth)){

                    //set cookie
                    $this->setCookie('auth', $auth);

                    $this->redirect()->toUrl('/admin');
                } else {

                    echo "Wrong email or password!";
                    exit;

                }
            } else {

                echo "You must fill email and password";
                exit;
            }

        }

        $cookie = $this->getRequest()->getCookie();
        if (!empty($cookie) && !empty($cookie->offsetExists('auth'))) {
            $auth =  $cookie->offsetGet('auth');

            if( \App\Auth::authByJWT($auth)){

                $this->setCookie('auth', $auth);
                $this->redirect()->toUrl('/admin');
            }
        }


    }
}
