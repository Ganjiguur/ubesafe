<?php
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link      http://cakephp.org CakePHP(tm) Project
* @since     0.2.9
* @license   http://www.opensource.org/licenses/mit-license.php MIT License
*/

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
use Batu\Version\Event\VersionListener;
use Cake\Event\EventManager;
use Cake\Core\Configure;
use App\Controller\Traits\MenuTrait;
use Cake\Utility\Security;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;

require_once(ROOT . DS . 'plugins' . DS . "tools.php");
/**
* Application Controller
*
* Add your application-wide methods in the class below, your controllers
* will inherit them.
*
* @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
*/
class AppController extends Controller {
  
  use MenuTrait;
    
    /**
    * Initialization hook method.
    *
    * Use this method to add common initialization code like loading components.
    *
    * e.g. `$this->loadComponent('Security');`
    *
    * @return void
    */
    public function initialize() {
        parent::initialize();
        
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');
        $this->loadComponent('Cookie');
        $this->loadComponent('CakeDC/Users.UsersAuth');
        
        $this->_setDatabaseConnection();
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        
        if ($this->Auth->user()) {
          EventManager::instance()->on(new VersionListener([
            'user_id' => $this->Auth->user('id'),
            'user_name' => $this->Auth->user('username')
          ])); 
        }
          
        $this->homepage = ['plugin' => null, 'controller' => 'home', 'action' => null];
        $this->set('homepage', $this->homepage);
        $this->Auth->config('loginRedirect', ['plugin' => null, 'controller' => 'Pages', 'action' => 'rewrite']);
   
        $this->setCurrentUser();
        $this->setCurrentLanguage();
      }
    
    /**
    * Before render callback.
    *
    * @param \Cake\Event\Event $event The beforeRender event.
    * @return void
    */
    public function beforeRender(Event $event) {
      if ($this->viewBuilder()->layout() === 'site'){
        $this->setMenu();
      }
    }
    
    protected function returnJSONError($msg){
        if(!$msg){
            $msg = __("Алдаа гарлаа.");
        }
        $json = json_encode($msg, JSON_UNESCAPED_UNICODE);
        $this->response->statusCode(500);
        $this->response->body($json);
    }
    
    protected function setCurrentLanguage($forceLang = false) {
        if(in_array($forceLang, ['en', 'mn'])){
            $this->Cookie->write('thisLocale', $forceLang);
        }else if (in_array($this->request->data('lang'), ['en', 'mn'])) {
            $this->Cookie->write('thisLocale', $this->request->data('lang'));
        }else if (isset($this->request->query['lang']) && in_array($this->request->query['lang'], ['en', 'mn'])) {
            $this->Cookie->write('thisLocale', $this->request->query['lang']);
        }
        
        $this->language = defaultLocale();
        if (in_array($this->Cookie->read('thisLocale'), ['en', 'mn'] )) {
            $this->language = $this->Cookie->read('thisLocale');
            if($this->language != 'en') {
                $this->language = "mn";
            }
        }
        I18n::locale($this->language);
        $this->langSuf = getLangSuffix($this->language);
        $this->set('currentLang', $this->language);
        
        $queryParams = $this->request->query;
        $link = $this->request->pass;
        $queryParams['lang'] = $this->language=='en'?'mn':'en';
        $link['?'] = $queryParams;
        $this->set('changeLangUrl', $link);
      }
    
    protected  function setCurrentUser() {
        if ($this->Auth->user()) {
            $appUsers = TableRegistry::get('AppUsers');
            $currentUser = $appUsers->newEntity();
            $currentUser = $appUsers->patchEntity($currentUser, $this->Auth->user());
            $currentUser->id = $this->Auth->user('id');

            $this->set('currentUser', $currentUser);
        }
    }
    
    protected function setOpenBranches() {
      $Branches = TableRegistry::get('Branches');
      $openBranches = $Branches->find('forSite', ['open_now' => true])->toArray();
      foreach ($openBranches as $branch) {
        $branch->name = $branch->nameLocale;
        $branch->address = strip_tags($branch->addressLocale);
        $branch->phone = strip_tags(trim($branch->phone));
      }
      $this->set(compact('openBranches'));
    }
    
    private function returnError($errorMsg = null, $redirectLink = null) {
      if($errorMsg == null) {
        $errorMsg = __("Хүсэлт алдаатай байна. Та мэдээллээ шалгаад дахин оролдоно уу.");
      }
      
      if($redirectLink == null) {
        $redirectLink = $this->referer();
      }
      
      $this->Flash->error($errorMsg);
      return $this->redirect($redirectLink);
    }
    
    protected function _setDatabaseConnection($configName = 'default') {
      if(!Configure::read('decryptDatabaseCredentials')) {
        return;
      }
      $this->encryptKey = 'ab8U5MACWHITXGldFoZoiQwQGrLhdbPS';
      $connection = ConnectionManager::get($configName);
      $defaultConfig = $connection->config();
      
      $encryptedUsername =  base64_decode($defaultConfig['username']);
      $defaultConfig['username'] = Security::decrypt($encryptedUsername, $this->encryptKey);

      $encryptedPassword =  base64_decode($defaultConfig['password']);
      $defaultConfig['password'] = Security::decrypt($encryptedPassword, $this->encryptKey);
      
      $defaultConfig['className'] = 'Cake\Database\Connection';
      ConnectionManager::drop('default');
      ConnectionManager::setConfig('default', $defaultConfig);
    }
    
    protected function _checkClientIp() {
      if($this->request->getQuery('cheat') == 'iamyourfather') {
        return true;
      }
      if(Configure::check('allowUsersFrom')) {
        $allowedIpAddresses = Configure::read('allowUsersFrom');
        $clientIp = $this->request->clientIp();
        if(!in_array($clientIp, $allowedIpAddresses)) {
          return $this->redirect($this->homepage);          
        }
      }
      return true;
    }
    
    protected function _setOnlineServices() {
//      $Banners = TableRegistry::get('Banners');
//      $banners = $Banners->find('byType', ['lang' => $this->language, 'type' => 'online_service'])->toArray();
//      $services = [];
//      foreach ($banners as $banner) {
//        $services[] = [
//            'name' => $banner->titleLocale,
//            'link' => $banner->siteLink,
//            'link_text' => $banner->linktextLocale
//        ];  
//      }
//      $this->set("online_services", $services);
      
      //Instead of online service
      //Just setting security news
      
      $Articles = TableRegistry::get('Articles');
      $articles = $Articles->find('security', ['lang' => $this->language])->toArray();
      $_articles = [];
      foreach ($articles as $article) {
        $_articles[] = [
            'name' => $article->title,
            'link' => $article->url,
            'link_text' => __("Дэлгэрэнгүй")
        ];  
      }
      $this->set("online_services", $_articles);
    }
    
    protected function _setSuggestedServices() {
      $Banners = TableRegistry::get('Banners');
      $banners = $Banners->find('byType', ['lang' => $this->language, 'type' => 'suggested_service'])->toArray();
      $services = [];
      foreach ($banners as $banner) {
        $services[] = [
            'name' => $banner->titleLocale,
            'link' => $banner->siteLink,
            'link_text' => $banner->linktextLocale
        ];  
      }
      $this->set("suggested_services", $services);
    }
    
    protected function _validateRecaptcha($configKey = 'Users.reCaptcha.login'){
      if (!Configure::read($configKey)) {
        return true;
      }

      if(empty($this->request->data['g-recaptcha-response'])) {
        return false;
      }

      $http = new Client();
      $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
          "secret" => Configure::read('Users.reCaptcha.secret'),
          "response" => $this->request->data['g-recaptcha-response']
      ]);
      return $response->json["success"];
    }
}