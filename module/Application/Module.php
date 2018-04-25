<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Admin\Model\EntrySection;
use Admin\Model\EntrySectionTable;
use Admin\Form\EntrySections;
use Zend\Db\TableGateway\TableGateway;

use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module implements AutoloaderProviderInterface
{
    
    public function onBootstrap(MvcEvent $e)
    {
        // LIVE CHAT SESSION
        if(!isset($_SESSION['SzewSzcz_chatToggle']))
            $_SESSION['SzewSzcz_chatToggle'] = 'none';
        // stan polaczenia
        if(!isset($_SESSION['SzewSzcz_chatConnect'])) // disconnect, waiting, connected
            $_SESSION['SzewSzcz_chatConnect'] = 'disconnect';
        
        // LIVE CHAT STATE
        $chat_normal = $chat_waiting = $chat_connected = $chat_leave = 'none';
        // ustalanie ikony polaczenia oraz aktywnego kontekstu

        switch($_SESSION['SzewSzcz_chatConnect']) {
                case 'disconnect':
                        $chat_icon = '/img/live_chat/normal_chat.png';
                        $chat_normal = 'block';
                break;
                case 'waiting':
                        $chat_icon = '/img/live_chat/chat_loading.gif';
                        $chat_waiting = 'block';
                break;
                case 'connected':
                        $chat_icon = '/img/live_chat/connected_chat.png';
                        $chat_connected = 'block';
                        $chat_leave = 'block';
                break;
        }
        
        
        
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this -> initAcl($e);
        $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));

        $roles = array(4 => 'adviser', 3 => 'admin', 2 => 'member', 1 => 'guest');

        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $authService = new AuthenticationService();

        
        if ($authService->hasIdentity()) {
            $user_role = $authService->getIdentity()->role;
            $user_email = $authService->getIdentity()->email;
        }
        else {
            $user_role = 1;
        }

        $viewModel->user_role = $user_role;
        $viewModel->user_role_name = $roles[$user_role];
        $viewModel->user_email = isset($user_email) ? $user_email : null;
        
        // CHAT VALUES
        $viewModel->chat_normal = $chat_normal;
        $viewModel->chat_waiting = $chat_waiting;
        $viewModel->chat_connected = $chat_connected;
        $viewModel->chat_leave = $chat_leave;
        $viewModel->chat_icon = $chat_icon;
        
        return $viewModel;

    }
    

    public function initAcl(MvcEvent $e) {

        $acl = new Acl();

        $acl->addResource(new Resource('home'));
        $acl->addResource(new Resource('files'));
        $acl->addResource(new Resource('noscript'));
        $acl->addResource(new Resource('chat/adviser'));
        $acl->addResource(new Resource('chat/index/async'));
        $acl->addResource(new Resource('center'));
        $acl->addResource(new Resource('center/index'));
        $acl->addResource(new Resource('center/member'));
        $acl->addResource(new Resource('center/member/change-avatar'));
        $acl->addResource(new Resource('center/document/upload-progress-action'));
        $acl->addResource(new Resource('center/document/change-filename'));
        $acl->addResource(new Resource('center/document/delete-file'));
        $acl->addResource(new Resource('center/document'));
        $acl->addResource(new Resource('blog'));
        $acl->addResource(new Resource('blog/show'));
        $acl->addResource(new Resource('users'));
        $acl->addResource(new Resource('users/login'));
        $acl->addResource(new Resource('users/register'));
        $acl->addResource(new Resource('users/logout'));
        $acl->addResource(new Resource('admin'));
        $acl->addResource(new Resource('admin/blog'));
        $acl->addResource(new Resource('admin/blog/edit-entry'));
        $acl->addResource(new Resource('admin/blog/add-entry'));
        $acl->addResource(new Resource('admin/user-manager'));
        $acl->addResource(new Resource('admin/taxonomies'));
        
        $acl->addRole(new Role('guest'));
            $acl->allow('guest', 'home');
            $acl->allow('guest', 'noscript');
            $acl->allow('guest', 'chat/index/async');
            $acl->allow('guest', 'blog');
            $acl->allow('guest', 'blog/show');
            $acl->allow('guest', 'users');
            $acl->allow('guest', 'users/login');
            $acl->allow('guest', 'users/register');
            
        $acl->addRole(new Role('member'), 'guest');
            $acl->allow('member', 'files');
            $acl->allow('member', 'users/logout');
            $acl->allow('member', 'center');
            $acl->allow('member', 'center/index');
            $acl->allow('member', 'center/member');
            $acl->allow('member', 'center/member/change-avatar');
            $acl->allow('member', 'center/document');
            $acl->allow('member', 'center/document/upload-progress-action');
            $acl->allow('member', 'center/document/change-filename');
            $acl->allow('member', 'center/document/delete-file');
            $acl->allow('member', 'admin/blog');
            $acl->allow('member', 'admin/blog/edit-entry');
            $acl->allow('member', 'admin/blog/add-entry');
            
        $acl->addRole(new Role('adviser'), 'member');
            $acl->allow('adviser', 'chat/adviser');
            
        $acl->addRole(new Role('admin'), 'adviser');
            $acl->allow('admin', 'admin');
            $acl->allow('admin', 'admin/user-manager');
            $acl->allow('admin', 'admin/taxonomies');

        
        //setting to view
        $e -> getViewModel() -> acl = $acl;
    }

    public function checkAcl(MvcEvent $e) {
        $auth = new AuthenticationService();
        
        if (!$auth->hasIdentity())
            $role_nr = 1;
        else
            $role_nr = $auth->getIdentity()->role;
        
        $roles = array(4 => 'adviser', 3 => 'admin', 2 => 'member', 1 => 'guest');
        
        $app = $e->getApplication();
        $route = $e -> getRouteMatch() -> getMatchedRouteName();

        $userRole = $roles[$role_nr];


        if (!$e->getViewModel()->acl->hasResource($route) || !$e->getViewModel()->acl->isAllowed($userRole, $route)) {
            $application   = $e->getApplication();
            $sm            = $application->getServiceManager();
            $sharedManager = $application->getEventManager()->getSharedManager();

            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch', function($e) use ($sm) {
                $controller = $e->getTarget();
                
                // delete when using zftools
                return $controller->redirect()->toRoute('home');
                
            },2);

        }
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
}
