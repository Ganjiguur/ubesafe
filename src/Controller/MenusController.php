<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Menus Controller
 *
 * @property \App\Model\Table\MenusTable $Menus
 */
class MenusController extends AppController
{
    public $paginate = [
        'limit' => 1000
    ];
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Admin');
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['ajaxSaveOrder']);   
    }
    
    public function index($id = null) {
      $this->Admin->checkTreeRecover();
      
        $menu_lang = $this->request->query('menu_lang');
        if(!in_array($menu_lang, ['mn', 'en'])) {
            $menu_lang = 'mn';
        }
        
        $menu = $this->_save(null, $menu_lang);
        if ($menu === false) { return; }
        
        $title_for_box = __('Шинэ {0} нэмэх', 'цэс');
        /* it will be used to know controller mode */
        $mode = 'add';

        /* id null bish uyed edit mode ruu shiljine.  **Tulga */
        if ($id != null) {
            $mode = 'edit';
            $title_for_box = __('{0} засах', 'Цэс');

            $menu = $this->_save($id, $menu_lang);
            if ($menu === false) { return; }
        }
        
        $menus = $this->Menus->find('threaded')
                        ->where(['language' => $menu_lang])
                        ->order(['lft' => 'ASC'])
                        ->toArray();
        
        $conditions = $id === null ? ['conditions'=>['language' => $menu_lang]] : ['conditions' => ['language' => $menu_lang, 'id !=' => $id]];
        $parentMenus = $this->Menus->find('treeList', ['spacer' => '-> ', 'order' => ['Menus.lft' => 'ASC']] + $conditions);
        
        $this->loadModel("Categories");
        $this->set('categories', $this->Categories->find('treeList', ['keyPath' => 'slug', 'valuePath' => 'name','spacer' => '->', 'order' => ['Categories.lft' => 'ASC']]));
        $this->loadModel("Pages");
        $this->set('pages', $this->Pages->find('list', ['site' => 'all', 'keyField' => 'slug', 'valueField' => function ($q) { return $q->get('label'); }])->order(['name' => 'ASC'])->where(['status' => true, 'language' => $menu_lang])->toArray());
        $this->loadModel("Products");
        $this->Products->behaviors()->Tree->config('scope', false);
        $this->set('products', $this->Products->find('treeList', ['site' => 'all', 'keyPath' => 'id', 'valuePath' => function ($q) { return $q->get('label'); }, 'spacer' => '->'])->order(['Products.lft' => 'ASC', 'site' => 'DESC'])->where(['active' => true])->toArray());

        $this->set(compact('menu', 'mode', 'title_for_box','parentMenus', 'menu_lang', 'menus'));        
    }
    
    protected function _save($id = null, $menu_lang) {
        if ($id === null) {
            $menu = $this->Menus->newEntity();
            $methods = 'post';
            $menu->attr = '';
        } else {
            $menu = $this->Menus->get($id);
            $methods = ['patch', 'post', 'put'];
        }
        
        if ($this->request->is($methods)) {
            $this->setMenuParams();
            if(empty($this->request->data['parent_id'])){
                $this->request->data['parent_id'] = null;
            }
            $menu = $this->Menus->patchEntity($menu, $this->request->data);

            if ($this->Menus->save($menu)) {
                $this->Flash->success(__('Амжилттай нэмэгдлээ.'));
                $this->redirect(['action' => 'index', 'menu_lang'=>$menu_lang]);
                return false;
            } else {
                $this->Flash->error(__('Хадгалах боломжгүй байна. Та дахин оролдоно уу.'));
            }
        }
        return $menu;
    }
    
    protected function setMenuParams() {
        switch ($this->request->data['link_type']) {
            case 'product': 
                $this->request->data['controller'] = 'products';
                $this->request->data['action'] = 'view';
                $this->request->data['link'] = $this->request->data['product'];
                break;
            case 'page': 
                $this->request->data['controller'] = 'pages';
                $this->request->data['action'] = 'view';
                $this->request->data['link'] = $this->request->data['page'];
                break;
            case 'category': 
                $this->request->data['controller'] = 'articles';
                $this->request->data['action'] = 'category';
                $this->request->data['link'] = $this->request->data['category'];
                break;
            case 'url': 
                $this->request->data['controller'] = null;
                $this->request->data['action'] = null;
                $this->request->data['link'] = $this->request->data['url'];
                break;
            case 'blank': 
                $this->request->data['link_type'] = 'blank';
                $this->request->data['controller'] = null;
                $this->request->data['action'] = null;
                $this->request->data['link'] = "";
                break;
            default: 
                $this->request->data['link_type'] = 'blank';
                $this->request->data['controller'] = null;
                $this->request->data['action'] = null;
                $this->request->data['link'] = "";
        }
    }
    
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $menu = $this->Menus->get($id);
        if ($this->Menus->delete($menu)) {
            $this->Flash->success(__('{0} устгагдлаа.', 'Цэс'));
        } else {
            $this->Flash->error(__('{0} устсангүй. Дахин оролдоно уу.', 'Цэс'));
        }
        return $this->redirect($this->referer());
    }
    
    public function ajaxSaveOrder() {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        
        if(empty($this->request->data["orders"])){
            return $this->returnJSONError(__('Bad request'));
        }
        
        $data = [];
        foreach ($this->request->data["orders"] as $id => $item) {
          $data[] = [
              'id' => $id,
              'lft' => $item['lft'],
              'rght' => $item['rght'],
              'level' => $item['level'],
              'parent_id' => $item['parent']
          ];
        }
        
        $entities = $this->Menus->patchEntities([],$data);
        
        if ($this->Menus->saveMany($entities, ['removeTree' => true])) {
            $this->response->body(json_encode(["msg"=>"Successfully"]));
        } else {
            $this->response->statusCode(500);
            return $this->returnJSONError($entities);
        }
    }
}
