<?php

namespace Files\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use HtImgModule\View\Model\ImageModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function memberAvatarAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $member = $this->params()->fromRoute('member');
        
        if (!$memberTable->checkPermission($member, $authService->getIdentity()->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $filename = $this->params()->fromRoute('document');
        $filepath = 'private/source/members/'.$member.'/'.$filename;
        if (file_exists($filepath) && $filename) {
            return new ImageModel($filepath);
        }
        else {
            return false;
        }
    }
    
    public function memberDocAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $member = $this->params()->fromRoute('member');
        $document = $this->params()->fromRoute('document');
        
        if (!$memberTable->checkPermission($member, $authService->getIdentity()->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $file = $this->params()->fromRoute('filename');
        
        $filename = 'private/source/members/'.$member.'/docs/'.$document.'/'.$file;

        if (file_exists($filename) && $file) {
            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($filename, 'r'));
            $response->setStatusCode(200);

            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Disposition', 'attachment; filename="' . $file . '"')
                ->addHeaderLine('Content-Length', filesize($filename));

            $response->setHeaders($headers);
            
            return $response;
        }
        else {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }

    public function memberDocPreviewAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $member = $this->params()->fromRoute('member');
        $document = $this->params()->fromRoute('document');
        
        if (!$memberTable->checkPermission($member, $authService->getIdentity()->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $filename = $this->params()->fromRoute('filename');
        
        $filepath = 'private/source/members/'.$member.'/docs/'.$document.'/'.$filename;
        
        if (file_exists($filepath) && $filename) {
            return new ImageModel($filepath);
        }
        else {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }


}

