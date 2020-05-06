<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use App\Auth;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Models\Employee;
use Models\User;

class AdminController extends AbstractActionController
{
    public function dashboardAction()
    {
        $userSession = \App\Auth::getAuth();

        if(empty($userSession)){

            header('Location: /login');
            exit;
        }

        return new ViewModel();
    }

    public function admininfoAction(){

        $userSession = \App\Auth::getAuth();

        if(empty($userSession)){

            header('Location: /login');
            exit;
        }

        return new ViewModel();
    }

    public function updateAdminAction(){

        $userSession = \App\Auth::getAuth();

        if(empty($userSession)){

            header('Location: /login');
            exit;
        }

        $data = $this->params()->fromPost();

        if(empty($data['email'])){

            die('You need to fill the email');

        } else if(strlen($data['email']) > 100) {

            die('The email must be maximum 100 characters long');

        } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

            die('You need to fill a valid email');

        } else if (empty($data['actual-password'])) {

            die('You need to fill the actual password');

        } else if(!(Auth::hashPassword($data['actual-password']) == $userSession['user_password'])){

            die('Your entered password does not match the actual password');

        } else if (empty($data['new-password'])) {

            die('You need to fill the new password');

        } else if (strlen($data['new-password']) < 8) {

            die('The new-password must be at least 8 characters long');

        } else if (empty($data['confirm-password'])) {

            die('You need to fill the confirm password');

        } else if (!($data['confirm-password'] == $data['new-password'])) {

            die('New Password and Confirm password must match!');
        }

        $serviceUser = new User();

        $fields = [

            'user_email' => $data['email'],
            'user_password' => Auth::hashPassword($data['new-password'])
        ];

        $serviceUser->update($fields, $userSession['user_id']);

        echo 'done';
        exit;

    }

    public function addemployeeAction(){

        $userSession = \App\Auth::getAuth();

        if(empty($userSession)){

            header('Location: /login');
            exit;
        }

        return new ViewModel();
    }

    public function addEmpAction(){

        $userSession = \App\Auth::getAuth();

        if(empty($userSession)){

            header('Location: /login');
            exit;
        }

        $data = $this->params()->fromPost();

        if(empty($data['name'])){

            die('You need to fill the name');

        } else if(empty($data['position'])){

            die('You need to fill the position');

        } else if(empty($data['office'])){

            die('You need to fill the office');

        } else if(empty($data['age'])){

            die('You need to fill the age');

        } else if(empty($data['salary'])){

            die('You need to fill the salary');

        } else if(empty($data['email'])){

            die('You need to fill the email');

        } else if(strlen($data['email']) > 100) {

            die('The email must be maximum 100 characters long');

        } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

            die('You need to fill a valid email');

        }

        $serviceEmployee = new Employee();

        if(!empty($serviceEmployee->getEmployeeByEmail($data['email']))){

            die('This employee is already in your account');
        }

        $serviceEmployee->addEmployee($data['name'], $data['position'], $data['office'], $data['age'],
            $data['salary'], $data['email']);


        echo 'done';
        exit;
    }
}
