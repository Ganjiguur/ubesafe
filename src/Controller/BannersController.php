<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\Entity;
use Cake\Event\Event;

/**
 * Banners Controller
 *
 * @property \App\Model\Table\BannersTable $Banners
 */
class BannersController extends AppController
{

//      home cover: 2000x920
//      home featured: 720x400
//      block: 1920x500
//      page cover: 840x250
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Admin');
        $this->loadComponent('FileManager', ['imageMaxWidth' => 2000]);
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
//        $this->Auth->allow(['rd']);
    }
    
    public $paginate = [
        'limit' => 25
    ];
    
    public function index()
    {
        $this->set('paginatorlimit', $this->paginate['limit']);
        $conditions = [];
        $qp = $this->request->query('searchText');
        if($qp != null) {
            $conditions['OR'] = [
                ['Banners.title LIKE' => '%' . $qp . '%'],
                ['Banners.title_en LIKE' => '%' . $qp . '%'],
                ['Banners.subtitle LIKE' => '%' . $qp . '%'],
                ['Banners.subtitle_en LIKE' => '%' . $qp . '%']
            ];
        }
        
        if (!empty($this->request->query('type'))) {
            $conditions = $conditions + ['Banners.type' => $this->request->query('type')];
        }
        
        $query = $this->Banners
                ->find('all')
                ->contain([
                    'Pages' => function ($q) {
                        return $q->select(['Pages.name', 'id', 'slug', 'site', 'language']);
                    },
                    'Products' => function ($q) {
                        return $q->select(['title', 'id', 'site']);
                    }
                ])
                ->where($conditions);
        $this->paginate['order'] = ['Banners.created' => 'desc'];
        $this->set('banners', $this->paginate($query));
        
        $banner = $this->Banners->newEntity();
        $this->set('types', $banner->types);
    }

    
    protected function _processImage(Entity $entity) {
        
        if (isset($this->request->data['delete_image']) && ($this->request->data['delete_image'] == 'true' || $_FILES['image_file']['size'] > 0)) {
            $this->FileManager->deleteFile($entity->get("media"));
            $this->request->data['media'] = null;
        }
        if (isset($_FILES['image_file']) && $_FILES['image_file']['size']) {
            $result = $this->FileManager->processImage($_FILES['image_file'], true, 'banner');
            if(!$result['success']) {
              $_msg = isset($result['msg']) ? $result['msg'] : __("Error durin image upload process.");
              $this->Flash->error($_msg);
              return false;
            }
            
            $this->request->data['media'] = $result == true ? $result['link'] : null;
        }
        return true;
    }
    
    protected function _processMedia(Entity $entity) {
        switch ($this->request->getData("media_type")) {
            case "image":
                return $this->_processImage($entity);
            default:
                return true;
        }
    }
    
    /**
     * View method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $banner = $this->Banners->get($id);

        $this->set('banner', $banner);
    }

    public function crud($id = null) {
        if ($id === null) {
            $banner = $this->Banners->newEntity();
            $banner->submit_text = __("Send");
            $methods = 'post';
            $this->set('title_for_page', __('Баннер үүсгэх'));
        } else {
            $banner = $this->Banners->get($id, ['contain' => [
                'Products' => function ($q) {
                    return $q->select(['id', 'title']);
                },
                'Pages' => function ($q) {
                    return $q->select(['id', 'name']);
                }
            ]]);
            $methods = ['patch', 'post', 'put'];
            $this->set('title_for_page', __('Баннер засах'));
        }
        
        $saveEntity = false;
        if ($this->request->is($methods)) {
            $saveEntity = true;
            if(!$this->_processMedia($banner)) {
                $saveEntity = false;
            }
        }
        
        if ($saveEntity) {
            $this->_fixDate('startdate');
            $this->_fixDate('enddate');
            
            $this->_fixType();
            
            $toDelete = $this->Admin->combineAssociations($banner, ['pages', 'products'], 'locations');
            unset($banner['pages']);
            unset($banner['products']);
            $banner = $this->Banners->patchEntity($banner, $this->request->getData());
            if ($this->Banners->save($banner)) {
                if (count($toDelete) > 0) {
                    $this->Banners->Locations->deleteAll(['id IN' => $toDelete]);
                }
                $this->Flash->success(__('The banner has been saved.'));
                return $this->redirect([$banner->id]);
            }
            $this->Flash->error(__('The banner could not be saved. Please, try again.'));
        }
        
        $this->loadModel("Pages");
        $pages = $this->Pages->find('list')->where(['archived' => false])->order(['name'])->toArray();
        
        $this->loadModel("Products");
        $products = $this->Products->find('treeList', ['spacer' => '--> ', 'order' => ['Products.lft' => 'ASC'], 'conditions' => ['archived' => false]])->toArray();

        $this->set(compact('banner', 'pages', 'products'));
    }
    
    protected function _fixDate($name) {
        if($this->request->data['never_end']) {
            $this->request->data[$name] = null;
            return;
        }
        if(isset($this->request->data[$name])){
            $this->request->data[$name] = date_create_from_format('Y.m.d', $this->request->data[$name]);
        }
    }
    
    protected function _fixType() {
      $this->request->data['homepage'] = 0;
//        if( $this->request->data['type'] == 'block') {
//            return;
//        } else {
//            $this->request->data['homepage'] = in_array($this->request->data['type'], $this->Banners->newEntity()->homeTypes());
//        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $banner = $this->Banners->get($id, ['site' => 'all']);
        if ($this->Banners->delete($banner)) {
            $this->Flash->success(__('The banner has been deleted.'));
        } else {
            $this->Flash->error(__('The banner could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
//    public function rd($id = null) {
//        $rdLink = $this->request->getQuery('url');
//        if($id) {
//            $this->Banners->incrementClickCount($id);
//        }
//        header('Location: '.$rdLink);
//        die();
//    }
}
