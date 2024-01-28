<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;

require_once ROOT . DS . 'plugins' . DS . "tools.php";

/**
* User helper
*/
class AppUserHelper extends Helper
{
    
    /**
    * Default configuration.
    *
    * @var array
    */
    protected $_defaultConfig = [];
    
    public $helpers = ['Html'];
    
    public function isImpersonated(){
        return $this->request->session()->read('impersonated') == true;
    }
    
    function isLoggedIn() {
        return $this->request->session()->check("Auth.User.id");
    }
    
    function isAdmin() {
        $session = $this->request->session();
        if ($session->check('Auth.User.id') && $session->read("Auth.User.role") == 'admin' || $session->read('Auth.User.is_superuser')) {
            return true;
        }
        return false;
    }
    
    function isMe($id){
        if(empty($id)) return false;
        return $this->request->session()->read('Auth.User.id') == $id;
    }
    
    function getUserMenu() {
        $dashboard = [['icon'=> 'dashboard', 'title'=> __('Dashboard'), 'action'=>["plugin"=>null,'controller'=>'Dashboard','action'=>'index']]];
        $menu = [];
        $modules = availableModules();
        if($this->isAdmin()) {
            $menu = $modules;
        } else {
            $userPermission = $this->request->session()->read('Auth.User.permission');
            foreach ($userPermission as $module) {
                array_push($menu, $modules[$module]);
            }
        }
        return array_merge($dashboard, $menu);
    }
    
}