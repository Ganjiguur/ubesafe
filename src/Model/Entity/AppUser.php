<?php

namespace App\Model\Entity;
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
use CakeDC\Users\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Utility\Security;

class AppUser extends User {
    
    const PERMISSION_KEY = 'FoZoiLwQGrLgdbHAwt1U5MACWJF2XGen';


    protected $_accessible = [
    'username' => true,
    'email' => true,
    'password' => true,
    'confirm_password' => true,
    'full_name' => true,
    'token' => true,
    'token_expires' => true,
    'api_token' => true,
    'activation_date' => true,
    //tos is a boolean, coming from the "accept the terms of service" checkbox but it is not stored onto database
    'tos' => true,
    'tos_date' => true,
    'active' => true,
    'social_accounts' => true,
    'current_password' => true,
    'bio' => true,
    'photo' => true,
    'last_login' => true,
    'permission' =>  true,
    'role' => true
    ];
    
    protected function _getPhotoUrl()
    {
        if(!empty($this->photo)) return $this->photo;
        if(!empty($this->avatar)){
            $avatar = $this->avatar;
            if($this->_properties['social_accounts'][0]['provider'] =='Twitter'){
                return str_replace('square', 'large', $avatar);
            }
            return str_replace('square', '400x400', $avatar);
        }
        return Configure::read('Users.Avatar.placeholder');
    }
    
    protected function _getUrl(){
        return Router::url(['controller' => 'profile', 'action' => $this->username]);
    }
}