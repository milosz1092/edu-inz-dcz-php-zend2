<?php

namespace Chat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Chat\Model\Request;
use Chat\Model\Message;
use DateTime;
use DateInterval;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        //return new ViewModel();
    }

    protected function updateAdviserOutsAction($current_time)
    {
        $requestTable = $this->getServiceLocator()->get('RequestTable');
        
        $date = new DateTime($current_time);
        $date->sub(new DateInterval('PT10S'));
        $date = $date->format('Y-m-d H:i:s');
        
        $requestTable->refreshAdviser($date);
        
        return 1;
    }

    protected function updateLastTimeAction($data)
    {
        $requestTable = $this->getServiceLocator()->get('RequestTable');
        
        $request = new Request();
        $request->exchangeArray($data);
        
        $requestTable->setUserLastTime($request);
        
        return 1;
    }

    protected function checkConnectionAction($ss_id)
    {
        $requestTable = $this->getServiceLocator()->get('RequestTable');
        return $requestTable->checkAdviserConnected($ss_id);
    }
    
    protected function initializeAction($data)
    {
        $requestTable = $this->getServiceLocator()->get('RequestTable');
                    
        $request = new Request();
        $request->exchangeArray($data);

        /*echo '<pre>';
        print_r($request);
        echo '</pre>';*/
        
        $requestTable->addUserRequest($request);

        return 1;
    }

    protected function cancelAction($ss_id)
    {
        $requestTable = $this->getServiceLocator()->get('RequestTable');
        $requestTable->disconnectUser($ss_id);

        return 1;
    }

    public function asyncAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
                                
        if ($authService->hasIdentity() && isset($authService->getIdentity()->role) && $authService->getIdentity()->role != 1) {
            $user_id = $authService->getIdentity()->id;
        } else 
            $user_id = 0;

        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            switch($post->type) {
                case 'change_chat_window_state':
                    if ($post->window_state == 'block') {
                        $_SESSION['SzewSzcz_chatToggle'] = 'block';
                    }
                    else if ($post->window_state == 'none') {
                        $_SESSION['SzewSzcz_chatToggle'] = 'none';
                    }
                break;
                case 'change_chat_status':
                    $_SESSION['SzewSzcz_chatConnect'] = $post->chat_state;

                    switch($post->chat_state) {
                        case 'waiting':
                            $this->initializeAction(array('ss_id' => session_id(), 'user' => $user_id, 'ip' => $_SERVER['REMOTE_ADDR'], 'last_time_user' => date("Y-m-d H:i:s"), 'browser' => $_SERVER['HTTP_USER_AGENT']));
                        break;
                        case 'disconnect':
                            $this->cancelAction(session_id());
                        break;
                    }
                break;
                case 'chat_check_connected':
                    $this->updateAdviserOutsAction(date("Y-m-d H:i:s"));
                    $this->updateLastTimeAction(array('ss_id' => session_id(), 'last_time_user' => date("Y-m-d H:i:s")));
                    
                    return new JsonModel(array(
                        $this->checkConnectionAction(session_id()),
                    ));
                break;
                case 'send_msg':
                    if ($this->request->isPost()) {
                        $post = $this->request->getPost();

                        $messageTable = $this->getServiceLocator()->get('MessageTable');

                        $msg = new Message();
                        $msg->exchangeArray(array(
                            'req_id' => session_id(),
                            'from_type' => 'u',
                            'time' => date("Y-m-d H:i:s"),
                            'content' => $post->msg
                        ));

                        return new JsonModel(array(
                            $messageTable->sendMsg($msg),
                        ));
                    }
                break;
                case 'get_msg':
                    if ($this->request->isPost()) {
                        $post = $this->request->getPost();

                        $messageTable = $this->getServiceLocator()->get('MessageTable');

                        $rowset = $messageTable->getMsg(session_id(), $post->from, $post->last_msg);
                        
                        return new JsonModel(array(
                            'messages' => $rowset->toArray(),
                        ));
                    }
                break;
                default:
                    return new JsonModel(array(
                        '',
                    ));
            }
        }
    }
}

