<?php
namespace Application\Controller;


use App\Auth;
use Laminas\Mvc\Controller\AbstractActionController;

class LogOutController extends AbstractActionController{

    public function indexAction()
    {
        Auth::logout();
        header("Location: /login");
        exit;
    }
}