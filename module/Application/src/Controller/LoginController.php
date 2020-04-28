<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function loginAction(){

        $data = $this->params()->fromPost();

        if(empty($data['email'])){

            echo 'The email field is empty';
            exit;
        } else if(empty($data['password'])){

            echo 'The password field is empty';
            exit;
        }

        $serviceUser = new \Models\User();
        $user = $serviceUser->getByEmailAndPassword($data['email'], $data['password']);

        if(!empty($user)){

            $this->redirect()->toUrl('/admin');
        } else {

            echo 'Wrong email or password';
            exit;
        }
    }
}
