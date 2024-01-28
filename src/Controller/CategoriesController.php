<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController {

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
    
    $category = $this->_save();
    if ($category === false) {
      return;
    }

    $title_for_box = __('Шинэ {0} нэмэх', 'категори');
    $mode = 'add';

    if ($id != null) {
      $mode = 'edit';
      $title_for_box = __('{0} мэдээллийг засах', 'Категорийн');

      $category = $this->_save($id);
      if ($category === false) {
        return;
      }
    }

    $categories = $this->Categories->find('threaded')
                    ->contain(['Articles' => function ($q) {
                          return $q->select(['Articles.id']);
                        }])
                    ->order(['lft' => 'ASC'])->toArray();
    $conditions = $id === null ? [] : ['conditions' => ['id !=' => $id]];
    $parents = $this->Categories->find('treeList', ['spacer' => '-> '] + $conditions);

    $this->set(compact('category', 'mode', 'title_for_box', 'parents', 'categories'));
  }

  protected function _save($id = null) {
    if ($id === null) {
      $category = $this->Categories->newEntity();
      $methods = 'post';
    } else {
      $category = $this->Categories->get($id);
      $methods = ['patch', 'post', 'put'];
    }

    if ($this->request->is($methods)) {
      $oldSlug = $category->slug;
      $category = $this->Categories->patchEntity($category, $this->request->data);
      $updateMenu = $category->dirty('slug') && !empty($oldSlug);
      if (empty($this->request->data['parent_id'])) {
        $this->request->data['parent_id'] = null;
      }
      if ($this->Categories->save($category)) {
        if ($updateMenu) {
          $category->updateMenu($oldSlug);
        }
        $this->Flash->success(__('Амжилттай засагдлаа.'));
        $this->redirect(['action' => 'index']);
        return false;
      } else {
        $this->Flash->error(__('Хадгалах боломжгүй байна. Та дахин оролдоно уу.'));
      }
    }
    return $category;
  }

  public function delete($id = null) {
    $this->request->allowMethod(['post', 'delete']);
    $category = $this->Categories->get($id);
    $this->loadModel("Articles");
    $query = $this->Articles->find();

    $query->matching('Categories', function ($q) use ($category) {
      return $q->where(['Categories.id' => $category->id]);
    });
    $articles = $query->toArray();
    if (count($articles) > 0) {
      $this->Flash->error(__('Уг категоритой холбогдсон нийтлэл байна. Та эхлээд тэдгээр нийтлэлүүдийг устгана уу.'));
      return $this->redirect($this->referer());
    }

    if ($this->Categories->delete($category)) {
      $this->Flash->success(__('{0} устгагдлаа.', 'Категори'));
    } else {
      $this->Flash->error(__('{0} устсангүй. Дахин оролдоно уу.', 'Категори'));
    }
    return $this->redirect($this->referer());
  }

  public function ajaxSaveOrder() {
    $this->autoRender = false;
    $this->request->allowMethod(['post', 'ajax']);

    if (empty($this->request->data["orders"])) {
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

    $entities = $this->Categories->patchEntities([], $data);

    if ($this->Categories->saveMany($entities, ['removeTree' => true])) {
      $this->response->body(json_encode(["msg" => "Successfully"]));
    } else {
      $this->response->statusCode(500);
      return $this->returnJSONError($entities);
    }
  }

}
