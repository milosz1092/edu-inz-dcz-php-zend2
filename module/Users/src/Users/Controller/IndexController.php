<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

use Zend\Session\SessionManager;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $authService = new AuthenticationService();

        if ($authService->hasIdentity())
            $user_role = $authService->getIdentity()->role;
        else
            $user_role = 1;

        $view = new ViewModel(array('user_role' => $user_role));

        return $view;
    }

    public function logoutAction()
    {
        $authService = new AuthenticationService();
        $authService->clearIdentity();
        
        $sessionManager = new SessionManager;
        $sessionManager->forgetMe();
        
        return $this->redirect()->toUrl('/');
    }

}

