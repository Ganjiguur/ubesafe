<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;

class ExtendsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('FileManager', ['imageMaxWidth' => 2000]);
    }
    
    private $image_types = [
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/jpg",
        "image/pjpg",
        "image/png",
        "image/x-png"
        ];

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        //allow method for ajax request
        $this->Security->config('unlockedActions', [
            'ajaxUploadImage',
            'ajaxUploadFile',
            'ajaxLoadImages',
            'ajaxDeleteImage']);
    }

    public function siteMeta($id = null) {
      $metaType = $this->request->query('type');
      if (!in_array($metaType, ['text', 'image', 'file'])) {
          $metaType = 'text';
      }
      $this->set("type", $metaType);
      
      if ($id === null) {
        $site_meta = $this->Extends->newEntity();
        $site_meta->site="";
        $site_meta->field="";
        $methods = 'post';
        $this->set('title_for_box', __("Шинэ мэдээлэл нэмэх"));
        $this->set('mode', 'add');
      } else {
        $site_meta = $this->Extends->get($id);
        $methods = ['patch', 'post', 'put'];
        $this->set('title_for_box', __("Мэдээлэл засах"));
        $this->set('mode', 'edit');
      }
        
      $saveEntity = false;
      if ($this->request->is($methods)) {
          $saveEntity = true;
          if($metaType == 'image' && !$this->_processImage($site_meta)) {
              $saveEntity = false;
          }
          
          if($metaType == 'file' && !$this->_processFile($site_meta)) {
              $saveEntity = false;
          }
      }
      
      if ($saveEntity) {
        
        $site_meta = $this->Extends->patchEntity($site_meta, $this->request->data);
        if ($this->Extends->save($site_meta)) {
            $this->Flash->success(__('Амжилттай хадгалагдлаа.'));
            return $this->redirect(['action' => 'siteMeta', 'type' => $metaType]);
        }
        
        $this->Flash->error(__('Хадгалах боломжгүй байна. Та дахин оролдоно уу.'));
        }
      
        $conditions = ['type' => $metaType];

        if (!$this->Auth->user('is_superuser')) {
            array_push($conditions, ['editable' => true]);
        }

        $queryAll = $this->Extends->find('all')->where($conditions)->order(['id' => 'asc']);
        $metas = $queryAll->all();
        

        $this->set(compact('metas', 'site_meta'));
    }
    
    protected function _processImage($entity) {
      if (isset($this->request->data['delete_image']) && ($this->request->data['delete_image'] == 'true' || $_FILES['image_file']['size'] > 0)) {
          $this->FileManager->deleteFile($entity->data);
          $this->request->data['data'] = null;
      }
      if (isset($_FILES['image_file']) && $_FILES['image_file']['size']) {
          $result = $this->FileManager->processImage($_FILES['image_file'], true, 'site_meta');
          if(!$result['success']) {
              $this->Flash->error($result['msg']);
              return;
          }
          $this->request->data['data'] = $result == true ? $result['link'] : null;
      }
      return true;
    }
    
    protected function _processFile($entity) {
      if (isset($this->request->data['delete_file']) && ($this->request->data['delete_file'] == 'true' || $_FILES['upload_file']['size'] > 0)) {
          $this->FileManager->deleteFile($entity->data);
          $this->request->data['data'] = null;
      }
      if (isset($_FILES['upload_file']) && $_FILES['upload_file']['size']) {
          $result = $this->FileManager->processFile($_FILES['upload_file'], 'site_meta');
      if(!$result['success']) {
          $this->Flash->error($result['msg']);
          return;
      }
      $this->request->data['data'] = $result == true ? $result['link'] : null;
      }
      return true;
    }

    private function _getDirname() {
        if ($this->request->data('dirname') != null) {
            return $this->request->data('dirname');
        }
        return ($this->Auth->user("id")) ? $this->Auth->user("id") : "public";
    }

    public function ajaxUploadImage() {
        $this->autoRender = false; // We don't render a view in this example
        $this->request->allowMethod(['post', 'ajax']); // No direct access via browser URL      
        $dirname = $this->_getDirname();
        
        $image = $this->request->data('file');
        if(!in_array($image['type'], $this->image_types)) {
            $this->response->statusCode(500);
            $this->response->body(json_encode(['success' => false, 'msg' => 'Wrong image format!']));
            return;
        }
        if(empty(strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)))) {
            $image['name'] .= '.jpeg';
        }
        $result = $this->FileManager->processImage($image, true, $dirname);
        if($result['success']) {
          unset($result['filepath']);
            $this->response->body(json_encode($result));
        } else {
            $this->response->statusCode(500);
            $this->response->body(json_encode($result));
            return;
        }   
    }

    public function ajaxUploadFile() {
        $this->autoRender = false; // We don't render a view in this example
        $this->request->allowMethod(['post', 'ajax']); // No direct access via browser URL
        $dirname = $this->_getDirname();

        $result = $this->FileManager->processFile($this->request->data('file'), $dirname);
        $this->response->body(json_encode($result, $this->encodeFormat));
    }

    public function ajaxLoadImages() {
        $this->autoRender = false; // We don't render a view in this example
        $this->request->allowMethod(['post', 'ajax']); // No direct access via browser URL
        $dirname = $this->_getDirname();

        $result = $this->FileManager->loadImages($dirname);
        $this->response->body(json_encode($result, $this->encodeFormat));
    }

    public function ajaxDeleteImage() {
        $this->autoRender = false; // We don't render a view in this example
        $this->request->allowMethod(['post', 'ajax']); // No direct access via browser URL
        $result = $this->FileManager->deleteFile($this->request->data('src'));
        $this->response->body(json_encode($result, $this->encodeFormat));
    }

    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $meta = $this->Extends->get($id);
        if ($this->Extends->delete($meta)) {
            $this->Flash->success(__('Амжилттай устгагдлаа.'));
        } else {
            $this->Flash->error(__('Мэдээлэл устсангүй. Та дахин оролдоно уу.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function getBitcoin() {
      $http = new Client();
      $response = $http->get('https://api.coindesk.com/v1/bpi/currentprice.json');
      return $response->json['bpi']['USD'];
    }

}
