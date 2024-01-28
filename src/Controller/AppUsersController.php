<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\AppUsersTable;
use Cake\Event\Event;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Controller\Traits\LoginTrait;
use CakeDC\Users\Controller\Traits\ReCaptchaTrait;
use CakeDC\Users\Controller\Traits\ProfileTrait;
use CakeDC\Users\Controller\Traits\RegisterTrait;
use CakeDC\Users\Controller\Traits\SimpleCrudTrait;
use CakeDC\Users\Controller\Traits\SocialTrait;
use Cake\Core\Configure;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

class AppUsersController extends AppController {
    
    //update redirect after login in vendor app
    use LoginTrait;
    use ReCaptchaTrait;
    use ProfileTrait;
    use RegisterTrait;
    use SimpleCrudTrait;
    
    public $paginate = [
    'AppUsers'=>[],
    'Articles'=>['limit' => 25,'order' => ['Articles.created' => 'desc']]
    ];
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('FileManager', ['imageMaxWidth' => 300]);
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            // LoginTrait
                'login',
                'verify',
                // RegisterTrait
                // PasswordManagementTrait used in RegisterTrait
                'changePassword',
                'resetPassword',
                'requestResetPassword',
                // UserValidationTrait used in PasswordManagementTrait
                'resendTokenValidation',
        ]);
        //allow method for ajax request
        $this->Security->config('unlockedActions', ['ajaxUpdateToken', 'ajaxUpdateSession']);
        
        if($this->request->action == 'login') {
          $this->_checkClientIp();
        }
        
    }
    
    public function index($role = null) {
        $table = $this->loadModel();
        $tableAlias = $table->alias();
        
        $conditions = ['is_superuser' => false];
        $qp = $this->request->query('searchText');
        if ($qp != null) {
            array_push($conditions, ["OR" => [['full_name LIKE' => '%' . $this->request->query('searchText') . '%'], ['username LIKE' => '%' . $this->request->query('searchText') . '%']]]);
        }
        
        if ($role) {
            array_push($conditions, ['role' => $role]);
        }
        
        $query = $table->find('all')
        ->where($conditions);
        $this->set($tableAlias, $this->paginate($query));
    }
    
    public function edit($id = null) {
        if($id == null || !($this->Auth->user('role') == 'admin' || $this->Auth->user('is_superuser'))) {
            $id = $this->Auth->user('id');
        }
        $table = $this->loadModel();
        $tableAlias = $table->alias();
        $entity = $table->get($id, [
        'contain' => ['SocialAccounts']
        ]);
        $this->set($tableAlias, $entity);
        $this->set('tableAlias', $tableAlias);
        $this->set('_serialize', [$tableAlias, 'tableAlias']);
        
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return;
        }

        if (isset($this->request->data['delete_photo']) && ($this->request->data['delete_photo'] == 'true' || $_FILES['user_photo']['size'] > 0)) {
            $this->FileManager->deleteFile($entity->photo);
            $this->request->data['photo'] = null;
        }

        if (isset($_FILES['user_photo']) && $_FILES['user_photo']['size']) {
            $result = $this->FileManager->processImage($_FILES['user_photo'], true, "profile", false, false);
            if(!$result['success']) {
                $this->Flash->error($result['msg']);
                return;
            }
            $photoSize = [
            'x' => $this->request->data['x_photo'] / $this->request->data['preview_ratio'],
            'y' => $this->request->data['y_photo'] / $this->request->data['preview_ratio'],
            'width' => $this->request->data['width_photo'] / $this->request->data['preview_ratio'],
            'height' => $this->request->data['height_photo'] / $this->request->data['preview_ratio']
            ];
            $this->FileManager->cropAndResizeImage($result['link'], '', $photoSize['width'], $photoSize['height'], $photoSize['x'], $photoSize['y'], 300, 300);
            $this->request->data['photo'] = $result['link'];
        }
        
        $validation = "default";
        
        if (isset($this->request->data["password_update"]) && $this->request->data["password_update"] != "") {
            $this->request->data["password"] = $this->request->data["password_update"];
            $this->request->data["password_confirm"] = $this->request->data["password_update_confirm"];
            $validation = "register";
        }
        $entity = $table->patchEntity($entity, $this->request->data, ['validate' => $validation]);
        $singular = Inflector::singularize(Inflector::humanize($tableAlias));
        if ($table->save($entity)) {
            $this->Flash->success(__d('Users', 'The {0} has been saved', $singular));
            //reset session Auth.User data to update logged in user information on session
            if($this->Auth->user('id') == $id) {
                $this->Auth->setUser($entity->toArray());
            }
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__d('Users', 'The {0} could not be saved', $singular));
    }
    
    /**
    * Delete method
    *
    * @param string|null $id User id.
    * @return Response Redirects to index.
    * @throws NotFoundException When record not found.
    */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $table = $this->loadModel();
        $tableAlias = $table->alias();
        $entity = $table->get($id, [
        'contain' => []
        ]);
        $this->deletePhoto($entity->photo);
        $singular = Inflector::singularize(Inflector::humanize($tableAlias));
        if ($table->delete($entity)) {
            $this->Flash->success(__d('Users', 'The {0} has been deleted', $singular));
        } else {
            $this->Flash->error(__d('Users', 'The {0} could not be deleted', $singular));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    private function deletePhoto($photoUrl) {
        if (!$photoUrl) {
            return;
        }
        
        $this->FileManager->deleteFile($photoUrl);
        $this->request->data['photo'] = null;
    }
    
    public function view($id = null)
    {
        if($id == null || !($this->Auth->user('role') == 'admin' || $this->Auth->user('is_superuser'))) {
            $id = $this->Auth->user('id');
        }
        
        $table = $this->loadModel();
        $tableAlias = $table->alias();
        $entity = $table->get($id, [
            'contain' => []
        ]);
        $this->set($tableAlias, $entity);
        $this->set('tableAlias', $tableAlias);
    }
    
    public function ajaxUpdateToken() {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        $table = $this->loadModel();
        $user = $table->newEntity();
        
        if(empty($this->request->data["user_id"]) || empty($this->request->data["token_action"])){
            return $this->returnJSONError(__('Bad request'));
        }
        
        $user->id = $this->request->data["user_id"];
        
        if($this->request->data["token_action"] == "refresh") {
            $user->api_token = Text::uuid();
        }
        
        if($this->request->data["token_action"] == "remove") {
            $user->api_token = null;
        }

        if ($table->save($user)) {
            $this->response->body(json_encode(["msg" => "Successfully", 'token' => $user->api_token]));
        } else {
            $this->response->statusCode(500);
            return $this->returnJSONError($user->errors());
        }
    }
    
    public function ajaxUpdateSession() {
      //this method is doing nothing but auth session is updated
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
    }
}