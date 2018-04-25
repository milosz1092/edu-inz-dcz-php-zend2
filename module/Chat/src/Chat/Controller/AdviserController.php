<?php

namespace Chat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Chat\Model\Message;
use DateTime;
use DateInterval;

class AdviserController extends AbstractActionController
{

    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $requestTable = $this->getServiceLocator()->get('RequestTable');
                
                    $date = new DateTime(date("Y-m-d H:i:s"));
                    $date->sub(new DateInterval('PT20S'));
                    $date = $date->format('Y-m-d H:i:s');
                
                $rowset = $requestTable->getWaitingCalls($date);
                
                $viewModel = new ViewModel(array(
                    'calls' => $rowset,
                    'auth_id' => $authService->getIdentity()->id
                ));

                return $viewModel;
    }

    public function asyncAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
                                        
                if (isset($authService->getIdentity()->role) && $authService->getIdentity()->role > 2) {
                    $adviser_id = $authService->getIdentity()->id;
                } else 
                    $adviser_id = 0;

                if ($this->request->isPost()) {
                    $post = $this->request->getPost();
                }
                
                    switch($post->type) {
                        case 'req_accept_admin':
                            if ($this->request->isPost()) {
                                $post = $this->request->getPost();
                            
                                $requestTable = $this->getServiceLocator()->get('RequestTable');
                                $authService = $this->getServiceLocator()->get('AuthService');

                                    $date = new DateTime(date("Y-m-d H:i:s"));
                                    $date->sub(new DateInterval('PT20S'));
                                    $date = $date->format('Y-m-d H:i:s');

                                $requestTable->receiveCall($post->ss_id, $authService->getIdentity()->id, date("Y-m-d H:i:s"));
                                
                                return new JsonModel(array(
                                    $requestTable->checkUserConnected($post->ss_id, $date),
                                ));
                            }
                        break;
                        case 'cancel_chat_admin':
                            if ($this->request->isPost()) {
                                $requestTable = $this->getServiceLocator()->get('RequestTable');
                                $post = $this->request->getPost();
                                
                                $requestTable->disconnectAdviser($post->ss_id);
                                
                                return new JsonModel(array(
                                    $post->ss_id,
                                ));
                            }
                        break;
                        case 'update_req_list':
                            $requestTable = $this->getServiceLocator()->get('RequestTable');
                            
                                $date = new DateTime(date("Y-m-d H:i:s"));
                                $date->sub(new DateInterval('PT10S'));
                                $date = $date->format('Y-m-d H:i:s');
                            
                            $requestTable->refreshAdviser($date);
                            $rowset = $requestTable->getWaitingCalls($date);

                            $rowset_array = $rowset->toArray();
                            
                            return new JsonModel(array(
                                'calls' => $rowset_array,
                            ));
                        break;
                        case 'send_msg_admin':
                            if ($this->request->isPost()) {
                                $post = $this->request->getPost();
                                
                                $messageTable = $this->getServiceLocator()->get('MessageTable');
                                
                                $msg = new Message();
                                $msg->exchangeArray(array(
                                    'req_id' => $post->ss_id,
                                    'from_type' => 'a',
                                    'time' => date("Y-m-d H:i:s"),
                                    'content' => $post->msg
                                ));

                                return new JsonModel(array(
                                    $messageTable->sendMsg($msg),
                                ));
                            }
                        break;
                    case 'get_msg_admin':
                        if ($this->request->isPost()) {
                            $post = $this->request->getPost();

                            $messageTable = $this->getServiceLocator()->get('MessageTable');

                            $rowset = $messageTable->getMsg($post->ss_id, $post->from, $post->last_msg);

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

    public function receiveAction()
    {
        $ss_id = $this->params()->fromRoute('id');
        $requestTable = $this->getServiceLocator()->get('RequestTable');
        $authService = $this->getServiceLocator()->get('AuthService');
        
        $returned = $requestTable->checkEmptyCall($ss_id);
        
        if ($returned[0] == 0 && ($returned[1] != $authService->getIdentity()->id)) {
            return $this->redirect()->toUrl('/chat/adviser');
        }
        
        
        $chat_waiting = $chat_connected = $chat_leave = 'none';

        // ustalanie ikony polaczenia oraz aktywnego kontekstu
        $_SESSION['SzewSzcz_chatAdminConnect'] = 'waiting'; // automatyczne laczenie (odebranie rozmowy)

        switch($_SESSION['SzewSzcz_chatAdminConnect']) {
                case 'waiting':
                        $icon = '/img/live_chat/chat_loading.gif';
                        $chat_waiting = 'block';
                break;
                case 'connected':
                        $icon = '/img/live_chat/connected_chat.png';
                        $chat_connected = 'block';
                        $chat_leave = 'block';
                break;
        }
        
        
        
        // odebranie rozmowy przez doradce - zapis w bazie
        $requestTable->receiveCall($ss_id, $authService->getIdentity()->id, date("Y-m-d H:i:s"));
        
        
        $viewModel = new ViewModel(array(
            'icon' => $icon,
            'chat_waiting' => $chat_waiting,
            'chat_connected' => $chat_connected,
            'chat_leave' => $chat_leave,
            'ss_id' => $ss_id,
            'ss_info' => $requestTable->getSessionInfo($ss_id)
        ));

        return $viewModel;
    }


}

