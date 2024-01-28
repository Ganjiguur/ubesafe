<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

require_once ROOT . DS . 'plugins' . DS . "tools.php";

/**
* Articles Controller
*
* @property \App\Model\Table\ArticlesTable $Articles
*/
class ArticlesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('FileManager');
        $this->loadComponent('Admin');
        $this->loadComponent('Extend', [
        'getCategories' => ['index', 'category']
        ]);
    }
    
    private $locale = "";
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['category', 'view', 'jsonByCategory', 'jsonView']);
        $this->Security->config('unlockedActions', ['ajaxSaveArticle', 'category']);
        
        if ($this->language == "en") {
            $this->locale = "_en";
        }
    }
    
    public function beforeRender(Event $event) {
        parent::beforeRender($event);
        $this->set('paginatorlimit', $this->paginate['limit']);
    }
    
    public $paginate = [
      'limit' => 25,
      'order' => [
        'Articles.created' => 'desc'
      ]
    ];
    
    private function _articleQuery($category = null, $author = false, $addFields = null, $extraConditions = ['published' => 1], $order = ['Articles.created' => 'DESC'])
    {
        $conditions = array_merge(['Articles.archived' => false], (array)$extraConditions);
        $query = $this->Articles->find();
        if (isset($category)) {
            $query->matching('Categories', function ($q) use ($category) {
                return $q->where(['Categories.id' => $category]);
            });
        }
        
        if ($author) {
            $query->contain(['Users' => function ($q) {
                return $q->select(['Users.full_name', 'Users.username', 'Users.id']);
            }, 'Categories' => function ($q) {
                return $q->select(['Categories.name', 'Categories.name_en', 'Categories.id']);
            }]);
        }
        
        $fields = ['Articles.id', 'Articles.title', 'Articles.cover_image', 'Articles.slug', 'Articles.view_count', 'Articles.created'];
        if (isset($addFields)) {
            $fields = array_merge($fields, $addFields);
        }
        $conditions = $conditions + ['Articles.language' => $this->language];
        
        $query->where($conditions)->select($fields)->order($order);
        
        return $query;
    }
    
    private function _article_list($own, $parameter, $allCat = true, $addCondition = [], $catId = null)
    {
        $conditions = array_merge(['Articles.archived' => false], (array)$addCondition);
        if ($own) {
            $conditions = $conditions + ['Articles.user_id' => $this->Auth->user('id')];
        }
        $qp = $this->request->query('searchText');
        if ($qp != null) {
            $conditions = $conditions + ['Articles.title LIKE' => '%' . $this->request->query('searchText') . '%'];
        }
        switch ($this->request->query('fparam')) {
            case 'featured':
                $conditions['Articles.featured'] = true;
                break;
            case 'draft':
                $conditions['Articles.published'] = false;
                break;
            case 'deleted':
                $conditions['Articles.archived'] = true;
                break;
            default:
                break;
        }
        
        if (!empty($this->request->query('flang'))) {
            $conditions = $conditions + ['Articles.language' => $this->request->query('flang')];
        }
        
        $query = $this->Articles->find('all');
        if ($allCat) {
            if ($parameter) {
                $query->matching('Categories', function ($q) use ($parameter) {
                    return $q->where(['Categories.slug' => $parameter]);
                });
            }
        } else {
            $query->matching('Categories', function ($q) use ($catId) {
                return $q->where(['Categories.id' => $catId]);
            });
        }
        
        $fields = ['Articles.id', 'Articles.title', 'Articles.slug', 'Articles.view_count', 'Articles.created', 'Articles.published', 'Articles.featured','Articles.language', 'Articles.language', 'Articles.archived'];
        
        $query->contain(['Users' => function ($q) {return $q->select(['Users.username', 'Users.full_name', 'Users.id']);}, 'Categories' => function ($q) {return $q->select(['Categories.name', 'Categories.name_en', 'Categories.slug', 'Categories.id']);}])
        ->select($fields)
        ->where($conditions);
        
        $this->paginate['order'] = ['Articles.created' => "DESC"];
        $this->set('articles', $this->paginate($query));
    }
    
    public function index($cat_slug = null){
        $this->set('sideTitle', __("Нийтлэлүүд"));
        $this->_article_list(false, $cat_slug, true);
        $this->set('_serialize', true);
    }

    /**
    * Add method
    *
    * @return void Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $this->autoRender = false;

        $article = $this->Articles->newEntity();
        $article->user_id = $this->Auth->user('id');
        $article->archived = 0;
        $article->title="";
        $article->sub_title="";
        $article->slug="";
        $article->html="";
        $article->picture_homepage="";

        if ($this->Articles->save($article)) {
            return $this->redirect(['action' => 'edit', $article->id]);
        }else if($article->errors()){
            $this->Flash->error(__('The {0} could not be saved!', 'article'));
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('The {0} could not be saved! Please try again.', 'article'));
            return $this->redirect($this->referer());
        }
    }

    public function edit($id = null)
    {
        if(empty($id)) {
            $this->Flash->error(__('Wrong request!'));
            return $this->redirect(['action' => 'adminIndex']);
        }
        
        $article = $this->Articles->get($id, [
            'contain' => ['Categories', 'Users'],
        ]);
        
        $canSave = true;

        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['created_date']) && isset($this->request->data['created_time'])){
                $this->request->data['created'] = date_create_from_format('Y.m.d H:i', $this->request->data['created_date'] . ' '. $this->request->data['created_time']);
            }

            if (isset($this->request->data['delete_cover_image']) && ($this->request->data['delete_cover_image'] == 'true' || $_FILES['cover_image_file']['size'] > 0)) {
                $this->deleteCoverImages($article->cover_image);
                $this->request->data['cover_image'] = null;
            }
            if (isset($_FILES['cover_image_file']) && $_FILES['cover_image_file']['size']) {
                $dirname = ($this->Auth->user("id")) ? $this->Auth->user("id") : "public";
                $result = $this->FileManager->processImage($_FILES['cover_image_file'], true, $dirname, false, false);
                if(!$result['success']) {
                    $this->Flash->error($result['msg']);
                    $canSave = false;
                } else {
                    $ratio_square = $this->request->data['ratio_square'];
                    $squareSize = [
                    'x' => $this->request->data['x_square'] / $ratio_square,
                    'y' => $this->request->data['y_square'] / $ratio_square,
                    'width' => $this->request->data['width_square'] / $ratio_square,
                    'height' => $this->request->data['height_square'] / $ratio_square
                    ];
                    $ratio_cover = $this->request->data['ratio_cover'];
                    $coverSize = [
                    'x' => $this->request->data['x_cover'] / $ratio_cover,
                    'y' => $this->request->data['y_cover'] / $ratio_cover,
                    'width' => $this->request->data['width_cover'] / $ratio_cover,
                    'height' => $this->request->data['height_cover'] / $ratio_cover
                    ];
                    $this->FileManager->cropAndResizeImage($result['link'], '_square', $squareSize['width'], $squareSize['height'], $squareSize['x'], $squareSize['y'], 360,240);
                    $this->FileManager->cropAndResizeImage($result['link'], '', $coverSize['width'], $coverSize['height'], $coverSize['x'], $coverSize['y'], 1000, 1000);
                    $this->request->data['cover_image'] = $result['link'];
                }
            }
            
            $article = $this->Articles->patchEntity($article, $this->request->data);

            if($canSave) {
                if ($this->Articles->save($article)) {
                    $this->Flash->success(__('The {0} has been saved', 'article'));
                    return $this->redirect(['action' => 'edit', $id]);
                } else {
                    $this->Flash->error(__('The {0} could not be saved! Please try again.', 'article'));
                }
            }
        }

        $this->set('categories', $this->Articles->Categories->find('treeList', ['spacer' => '->'])->order(['Categories.lft' => 'ASC'])->toArray());

        $article->versions();
        $this->set('article', $article);
    }

    private function createCroppedImage($url, $w, $h, $dataName)
    {
        $x = $this->request->data['x_' . $dataName];
        $y = $this->request->data['y_' . $dataName];
        $width = $this->request->data['w_' . $dataName];
        $height = $this->request->data['h_' . $dataName];
        $this->FileManager->cropAndResizeImage($url, '_' . $dataName, $width, $height, $x, $y, $w, $h);
    }

    public function ajaxSaveArticle()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'ajax']);
        
        $requestEmpty = true;
        $article = $this->Articles->newEntity();
        
        if(!empty($this->request->data["body"])) {
            $article->html = $this->request->data["body"];
            $requestEmpty = false;
        }
        
        if($requestEmpty || empty($this->request->data["article_id"])){
            return $this->returnJSONError(__('Bad request'));
        }
        
        $article->id = $this->request->data["article_id"];

        if ($this->Articles->save($article)) {
            $this->response->body(json_encode(["msg" => "Successfully"]));
        } else {
            $this->response->statusCode(500);
            return $this->returnJSONError($article->errors());
        }
    }

    private function deleteCoverImages($coverUrl)
    {
        if (!$coverUrl) {
            return;
        }

        $this->FileManager->deleteFile($coverUrl);
        $url = $this->FileManager->appendFileName($coverUrl, '_square');
        $this->FileManager->deleteFile($url);
        $this->request->data['cover_image'] = null;
    }

    /**
    * Delete method
    *
    * @param string|null $id Article id.
    * @return \Cake\Network\Response|null Redirects to index.
    * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        $coverImage = $article->cover_image;
        if ($this->Articles->delete($article)) {
            $this->deleteCoverImages($coverImage);
            $this->Flash->success(__('Мэдээ устгагдлаа.'));
        } else {
            $this->Flash->error(__('Мэдээ устгах боломжгүй байна.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if(empty($article->title)) {
            $this->delete($id);
            return;
        }
        $article->archived = true;
        $article->published = false;
        $article->categories = [];
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Мэдээ устгагдлаа.'));
        } else {
            $this->Flash->error(__('Мэдээ устгах боломжгүй байна.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function restore($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        $article->archived = false;
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Мэдээ сэргээгдлээ.'));
        } else {
            $this->Flash->error(__('Мэдээг сэргээхэд алдаа гарлаа.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function view($slug = null) {
      if (!$slug) {
          return $this->redirect($this->homepage);
      }
      $this->viewBuilder()->layout("site");

      $preview = $this->request->query('preview');

      $article = $this->Articles->find('detail', ['lang' => $this->language, 'preview' => $preview, 'slug' =>$slug, 'site' => 'all'])->first();

      if (!$article) {
          return $this->redirect('/news');
      }
      $this->set('title_for_page', Text::truncate($article->title, 100, ['ellipsis' => '...','exact' => false]));

      if(!empty($article->cover_image)) {
          $this->set('og_image', $article->cover_image);
      }
      $this->set('og_description', $article->sub_title);

      $this->set('article', $article);
      if(!empty($article->categories)) {
        $ind = count($article->categories) > 1 ? 1 : 0;
        $category = $article->categories[$ind];
      } else {
        $category = $this->Articles->Categories->find('all')->where(['Categories.slug' => 'news'])->first();
      }
      $this->set('category', $category);

      $this->Articles->incrementViewCount($article->id);
      $this->setRelatedArticles($category->slug, $article->id);
    }
    
    protected function setRelatedArticles($catId, $articleId) {
      $articles = $this->Articles
              ->find('forSite', ['lang' => $this->language, 'cat' =>$catId, 'site' => 'all'])
              ->where(['Articles.id !=' => $articleId])
              ->limit(3)
              ->toArray();
      $this->set('articles', $articles);
    }
    
    public function category($slug = 'news') {
        $this->viewBuilder()->layout("site");
        
        if (is_numeric($slug)) {
          $conditions = ['Categories.id' => $slug];
        } else {
          $conditions = ['Categories.slug' => $slug];
        }

        $category = $this->Articles->Categories->find('all')
                ->where($conditions)
                ->first();
        
        if (!$category) { return $this->redirect('/news'); }
        
        $this->set('category', $category);
        $this->set('title_for_page', $category->nameLocale);
        
        $query = $this->Articles->find('forSite', ['lang' => $this->language, 'cat' =>$slug, 'site' => 'all']);
        
        $this->paginate['limit'] = 4;
        $this->set('articles', $this->paginate($query)->toArray());
        
        $this->_setOnlineServices();
        $this->_setSuggestedServices(3);
    }
    
    //REST functions /JSON/
    public function jsonByCategory($slug = 'news') {
      if (is_numeric($slug)) {
          $conditions = ['Categories.id' => $slug];
        } else {
          $conditions = ['Categories.slug' => $slug];
        }

        $category = $this->Articles->Categories->find('all')
->where($conditions)
                ->first();

        if (!$category) { 
          $articles = [];
        } else {
          $query = $this->Articles->find('forSite', ['lang' => $this->language, 'cat' =>$slug]);
        $articles = $this->paginate($query)->toArray();
        }
        
        $this->set('articles', [$articles]);
        $this->set('_serialize', ['articles']);
    }
    
    public function jsonView($slug = null) {
      $article = null;
      if ($slug) {
        $article = $this->Articles->find('detail', ['lang' => $this->language, 'slug' => $slug])->first();
      }
      $this->set('article', [$article]);
      $this->set('_serialize', ['article']);
    }
}