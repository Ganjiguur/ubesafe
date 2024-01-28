<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Event\Event;

/**
 * Dashboard Controller
 *
 * @property \App\Model\Table\DashboardTable $Dashboard
 */
class DashboardController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Admin');
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->setCurrentLanguage("mn");
    }
    
    public $paginate = [
        'limit' => 100
    ];
    
    private $titleFields = ['title', 'name', 'label', 'username', 'code', 'unique_key', 'year'];
    private $statusFields = ['active' => ['идэвхгүй болгосон байна.', 'идэвхжүүлсэн байна.'], 'status' => ['идэвхгүй болгосон байна.', 'идэвхжүүлсэн байна.'], 'published' => ['идэвхгүй болгосон байна.', 'идэвхжүүлсэн байна.']];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($user_id = null)
    {   
        $this->set('paginatorlimit', $this->paginate['limit']);
        
        $conditions = [];
        if(!in_array($this->Auth->user('role'), ['admin', 'superuser'])) {
            $conditions = ['user_id' => $this->Auth->user('id')];
        } else {
          $Users = TableRegistry::get('AppUsers');
          $this->set('users',$Users->find('all')
          ->order(['full_name'=>'ASC'])
          ->toArray());
          
          
          if ($user_id) {
            $conditions = ['user_id' => $user_id];
          }
        }
        
        $qp = $this->request->query('searchText');
        if ($qp != null) {
            $conditions = $conditions + ['content LIKE' => '%' . $qp . '%'];
        }
        
        
        $table = TableRegistry::get('version');
        $query = $table->find('all')->where($conditions);
        
        
        $this->paginate['order'] = ['created' => 'desc'];
        $records = $this->paginate($query);
        
        $logs = [];
        foreach($records as $record) {
            $identity = $record->model . '_' . $record->foreign_key . '_' . $record->version_id;
            $field = $record->field;
            $content = $record->content;
            if(isset($logs[$identity])) {
                $logs[$identity]['fields'][] = $field;
                $logs[$identity]['values'][] = $content;
            } else {
                $logs[$identity] = [
                    'model' => $record->model,
                    'model_id' => $record->foreign_key,
                    'username' => $record->user_name,
                    'user_id' => $record->user_id,
                    'date' => $record->created,
                    'action' => $record->action,
                    'fields' => [$field],
                    'values' => [$content],
                    'status_changed' => false
                ];
            }
            
            if(in_array($field, $this->titleFields)) {
                //it is title field
            }
            
            if(isset($this->statusFields[$field])) {
                $logs[$identity]['status_changed'] = true;
                $logs[$identity]['status_action'] = intval($content) ? $this->statusFields[$field][1] : $this->statusFields[$field][0];
            }
            
        }
        
        $this->set('titleFields' , $this->titleFields);
        $this->set('statusFields' , $this->statusFields);
        $this->set('logs', $logs);
    }
}
