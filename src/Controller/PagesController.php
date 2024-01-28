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

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Http\Client;
use Cake\Utility\Security;
use Cake\Utility\Hash;
use Cake\Mailer\Email;
use Cake\Http\Response;

require_once(ROOT . DS . 'plugins' . DS . "tools.php");

/**
* Static content controller
*
* This controller will render views from Template/Pages/
*
* @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
*/
class PagesController extends AppController
{
    
    public function initialize() {
        parent::initialize();
//        $this->loadComponent('Cookie');
        $this->loadComponent('Admin');
        $this->loadComponent('FileManager', ['imageMaxWidth' => 2000]);
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['display', 'rewrite', 'view', 'ajaxArticles', 'ajaxSendRequest', 'ajaxSendDonate']);
        $this->Security->config('unlockedActions', ['ajaxSavePage', 'ajaxArticles', 'ajaxSendRequest', 'ajaxSendDonate']);   
    }
    
    public $paginate = [
    'Articles' =>[
        'limit' =>3,
        'order' => ['Articles.created' => "DESC"]
        ],
    'Pages' => [
        'limit' =>50,
//        'order' => ['Pages.created'=>"DESC"]
        ]
    ];
    /**
    * Displays a view
    *
    * @return void|\Cake\Network\Response
    * @throws \Cake\Network\Exception\NotFoundException When the view file could not
    *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
    */
    public function display(...$path)
    {
      $count = count($path);
      if (!$count) {
          return $this->redirect($this->homepage);
      }
      if (in_array('..', $path, true) || in_array('.', $path, true)) {
          throw new ForbiddenException();
    }
      $page = $subpage = null;

      if (!empty($path[0])) {
          $page = $path[0];
      }
      if (!empty($path[1])) {
          $subpage = $path[1];
      }
      

      $this->viewBuilder()->layout("site");

      $this->set(compact('page', 'subpage'));
      
     
      try {
          $this->render(implode('/', $path));
      } catch (MissingTemplateException $e) { 
          if (Configure::read('debug')) {
              throw $e;
          }
          throw new NotFoundException();
      }
    }
    
    private function _page_list($addCondition = []) {
        $this->set('paginatorlimit', $this->paginate['Pages']['limit']);
        $conditions = array_merge(['Pages.archived' => false], (array)$addCondition);
        
        $qp = $this->request->query('searchText');
        if($qp != null) {
            $conditions = $conditions + ['Pages.title LIKE' => '%' . $qp . '%'];
        }
        
        switch ($this->request->query('fparam')) {
            case 'published':
                $conditions['Pages.status'] = true;
                break;
            case 'draft':
                $conditions['Pages.status'] = false;
                break;
            case 'deleted':
                $conditions['Pages.archived'] = true;
                break;
            default:
                break;
        }
        
        if (!empty($this->request->query('flang'))) {
            $conditions = $conditions + ['Pages.language' => $this->request->query('flang')];
        }
        
        $query = $this->Pages->find('all');
        
        $fields = ['Pages.id','Pages.title','Pages.name','Pages.slug','Pages.modified','Pages.created','Pages.description','Pages.status','Pages.language', 'Pages.archived'];
        
        $query->contain(['Users'=> function ($q) {
        return $q->select(['Users.username','Users.id']);}])
                ->select($fields)
//                ->order(['Pages.title' => 'ASC'])
                ->where($conditions);
        $this->paginate['Pages']['order'] = ['Pages.name' => "ASC"];
        
        $this->set('pages', $this->paginate($query));
    }
    
    /**
    * Index method
    *
    * @return \Cake\Network\Response|null
    */
    public function adminIndex()
    {
        $this->set('paginatorlimit', 25);
        $this->_page_list([]);
        $this->render("admin_index");
    }
    
    public function rewrite()
    {
        $message = __d('Users', 'You are not authorized to access that location.');
        
        if (!$this->Auth->user()) {
            return $this->redirect(['controller' => 'AppUsers','action' => 'login']);
        }
        
        return $this->redirect(['controller' => 'Dashboard','action' => 'index']);
    }
    
    /**
    * Add method
    *
    * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $this->autoRender = false;
        $page = $this->Pages->newEntity();
        $page->updated_by = $this->Auth->user('id');
        $page->archived = 0;
        $page->slug = "";
        $page->name = "";
        $page->html = "";
        
        if ($this->Pages->save($page)) {
            return $this->redirect(['action' => 'edit', $page->id]);
        } else {
            $this->Flash->error(__('The {0} could not be saved! Please try again.', 'page'));
            return $this->redirect($this->referer());
        }
    }
    
    /**
    * Edit method
    *
    * @param string|null $id Page id.
    * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
    * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        if(empty($id)) {
            $this->Flash->error(__('Wrong request!'));
            return $this->redirect(['action' => 'adminIndex']);
        }
        
        $page = $this->Pages->get($id);
        
        if (!isset($this->request->data['tabs'])) {
            $this->request->data['tabs'] = "";
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $oldSlug = $page->slug;
            $page = $this->Pages->patchEntity($page, $this->request->data);
            $updateMenu = $page->dirty('slug') && !empty($oldSlug);
            if ($this->Pages->save($page)) {
                if ($updateMenu) {
                    $page->updateMenu($oldSlug);
                }
                $this->Flash->success(__('The page has been saved.'));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__('The page could not be saved. Please, try again.'));
            }
        }
        $this->loadModel('Categories');
        $categories = $this->Categories->find('treeList', ['spacer' => '-> '])->toArray();
        $page->versions();
        $this->set(compact('page', 'categories'));
    }
    
    public function ajaxSavePage() {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        $requestEmpty = true;
        $page = $this->Pages->newEntity();
        
        if(!empty($this->request->data["body"])) {
            $page->html = $this->request->data["body"];
            $requestEmpty = false;
        }
        
        if($requestEmpty || empty($this->request->data["page_id"])){
            return $this->returnJSONError(__('Bad request'));
        }
        
        $page->id = $this->request->data["page_id"];
        
        if ($this->Pages->save($page)) {
            $this->response->body(json_encode(["msg"=>"Successfully"]));
        } else {
            $this->response->statusCode(500);
            return $this->returnJSONError($page->errors());
        }
    }
    
    /**
    * Delete method
    *
    * @param string|null $id Page id.
    * @return \Cake\Network\Response|null Redirects to index.
    * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The page has been deleted.'));
        } else {
            $this->Flash->error(__('The page could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'adminIndex']);
    }
    
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        $page->archived = true;
        $page->status = false;
        if ($this->Pages->save($page)) {
            $this->Flash->success(__('Вэб хуудас устгагдлаа.'));
        } else {
            $this->Flash->error(__('Вэб хуудcыг устгах боломжгүй байна.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function restore($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        $page->archived = false;
        if ($this->Pages->save($page)) {
            $this->Flash->success(__('Вэб хуудас сэргээгдлээ.'));
        } else {
            $this->Flash->error(__('Вэб хуудcыг сэргээхэд алдаа гарлаа.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function view($slug = null) {
        if ($slug == null) {
            return $this->redirect('/');
        }
        $this->viewBuilder()->layout("site");

        $conditions = ['Pages.slug' => $slug];
        if (is_numeric($slug)) {
            $conditions = ['Pages.id' => $slug];
        }
        if ($this->request->query('preview') != 'true') {
            array_push($conditions, ['status' => true]);
        }
        $page = $this->Pages->find('detail', ['lang' => $this->language])->where($conditions)->first();
        
        if (!$page) {
            return $this->redirect('/');
        }
        
        
        $this->set('title_for_page', Text::truncate($page->title, 100, ['ellipsis' => '...', 'exact' => false]));

        $this->set('page', $page);
       
        if (!empty($page->article_categories)) {
          $this->loadModel("Articles");
          $catId = intval($page->article_categories);
          $query = $this->Articles->find('forSite', ['lang' => $this->language, 'cat' =>$catId]);
        
          $this->set('articles', $this->paginate($query)->toArray());
        }
         
        switch ($page->template) {
          case 'home': $this->_handleHomePage($page); break;
          case 'news': $this->_handleNewsPage($page); break;
          default: break;   
        }
        
        if(!empty($page->forms)) {
          $this->handleFormSubmit();
        }

        if ($page->template != '') {
            $this->render($page->template);
        }
    }
    
    private function _handleHomePage($page) {
      $Articles = TableRegistry::get('Articles');
      $this->set('news', $Articles->find('forSite', ['lang' => $this->language])->limit(3)->toArray());

      $this->set('title_for_page', $page->title);
    }

    private function _handleNewsPage($page) {
        $this->set('paginatorlimit', 3);
        $conditions = ['lang' => $this->language];
        $qp = $this->request->query('searchText');
        if ($qp != null) {
            $conditions['search'] = $qp;
            $this->set('searchText', $qp);
        }
        $Articles = TableRegistry::get('Articles');
        // if (!empty($this->request->query('flang'))) {
        //     $conditions = $conditions + ['Pages.language' => $this->request->query('flang')];
        // }

        $articles = $Articles->find('forSite', $conditions)->limit(3);
        $this->set('news', $this->paginate($articles));
        $this->set('featuredNews', $Articles->find('forSite', ['lang' => $this->language])->first());        
        $this->set('hashtags', $Articles->Categories->find()->limit(5)->toArray());
    }

    function ajaxArticles() {
        $this->autoRender = false;
        $this->viewBuilder()->setLayout(false);
        $this->loadModel('Articles');
        $conditions = ['lang' => $this->language];
        $qp = $this->request->query('searchText');
        if ($qp != null) {
            $conditions['search'] = $qp;
            $this->set('searchText', $qp);
        }

        $articles = $this->Articles->find('forSite', $conditions)->limit(2);
        $this->set('news', $this->paginate($articles));
      
        $templateName = "/Element/articles/article";
        if($this->request->isMobile()) {
            $templateName = "/Element/articles/article_mobile"; 
        }

        $html = $this->render("/Element/articles/article")->body();

        $this->response->body($html);
    }

    public function ajaxSendRequest() {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        $emailTo = Configure::read("request-receiver-email");
        $formRows = $this->request->data;
  
        $email = new Email('default');
        $email->template('formdata', 'default');
        $email->viewVars(['formRows' => $formRows]);
        $email->viewVars(['type' => 'request']);
        $email->viewVars(['title' => 'Шинэ хамгаалалтын суудал авах хүсэлт']);
        $email->emailFormat("html");
        $email->from(["no-reply@carseat.mn" => "Carseat.mn"])
            ->to($emailTo)
            ->subject(__("Шинэ хамгаалалтын суудал авах хүсэлт ирлээ."))
            ->send();
       if($email) {
          $this->response->body(json_encode(['result' => 'success']));
        }else {
          $this->response->body(json_encode(['result' => 'error']));
        }
      }
  
      public function ajaxSendDonate() {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        $emailTo = Configure::read("request-receiver-email");
        $formRows = $this->request->data;
  
        $email = new Email('default');
        $email->template('formdata', 'default');
        $email->viewVars(['formRows' => $formRows]);
        $email->viewVars(['type' => 'donate']);
        $email->viewVars(['title' => 'Шинэ хамгаалалтын суудал хандивлах хүсэлт']);
        $email->emailFormat("html");
        $email->from(["no-reply@carseat.mn" => "Carseat.mn"])
            ->to($emailTo)
            ->subject(__("Шинэ хамгаалалтын суудал хандивлах хүсэлт ирлээ."))
            ->send();
        if($email) {
          $this->response->body(json_encode(['result' => 'success']));
        }else {
          $this->response->body(json_encode(['result' => 'error']));
        }
      }

}