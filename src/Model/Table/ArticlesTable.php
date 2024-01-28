<?php

namespace App\Model\Table;

use App\Model\Entity\Article;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Expression\QueryExpression;

/**
* Articles Model
*
* @property \Cake\ORM\Association\BelongsTo $Users
* @property \Cake\ORM\Association\BelongsToMany $Categories
*/
class ArticlesTable extends Table {
    
    /**
    * Initialize method
    *
    * @param array $config The configuration for the Table.
    * @return void
    */
    public function initialize(array $config) {
        parent::initialize($config);
        
        $this->table('articles');
        $this->displayField('title');
        $this->primaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['title', 'slug', 'html', 'archived', 'published'],
            'archivedField' => 'archived',
            'onlyDirty' => true
        ]);
        
        $this->belongsTo('Users', [
        'className' => 'AppUsers',
        'foreignKey' => 'user_id'
        ]);
        
        $this->belongsToMany('Categories', [
        'className' => 'Categories',
        'foreignKey' => 'article_id',
        'targetForeignKey' => 'category_id',
        'joinTable' => 'categories_articles'
        ]);
    }
    
    /**
    * Default validation rules.
    *
    * @param \Cake\Validation\Validator $validator Validator instance.
    * @return \Cake\Validation\Validator
    */
    public function validationDefault(Validator $validator) {
        $validator
        ->integer('id')
        ->allowEmpty('id', 'create');
        
        $validator
        ->requirePresence('title', 'create')
        ->notEmpty('title');
        
        $validator
        ->allowEmpty('cover_image');

        $validator
        ->allowEmpty('sub_title');
        
        $validator
        ->notEmpty('slug');
        
        $validator
        ->allowEmpty('description');
        
        $validator
        ->add('html', 'notBlank', ['rule' => 'notBlank', 'message' => __('Нийтлэл хоосон байж болохгүй.')])
        ->requirePresence('html', 'create');
        
        $validator
        ->add('published', 'valid', ['rule' => 'numeric'])
        ->requirePresence('published', 'create')
        ->allowEmpty('published');
        
        $validator
        ->add('featured', 'valid', ['rule' => 'numeric'])
        ->requirePresence('featured', 'create')
        ->allowEmpty('featured');
        
        $validator
        ->add('view_count', 'valid', ['rule' => 'numeric'])
        ->requirePresence('view_count', 'create')
        ->notEmpty('view_count');
        
        $validator
        ->allowEmpty('related_article');
        $validator
        ->allowEmpty('language');
        
        return $validator;
    }
    
    /**
    * Returns a rules checker object that will be used for validating
    * application integrity.
    *
    * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
    * @return \Cake\ORM\RulesChecker
    */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['slug', 'language']), '_isUnique', [
            'errorField' => 'slug',
            'message' => __('Нийтлэлийн линк давхардаж байна!')
        ]);
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
    
    public function isOwnedBy($articleId, $userId) {
        return $this->exists(['id' => $articleId, 'user_id' => $userId]);
    }
    
    public function findForSite(Query $query, array $options){
        $locale = (isset($options['lang']) && $options['lang'] == 'mn') ? '' : '_en';
        $searchText = (isset($options['search'])) ? $options['search'] : '';
        $fields = ['Articles.id', 'Articles.title', 'Articles.sub_title', 'Articles.cover_image', 'Articles.slug', 'Articles.featured', 'Articles.created', 'Articles.view_count'];
        

        $conditions = ['published'=>true, 'language'=>$options['lang']];
        
        if(!empty($searchText)) {
            $conditions['title'] = '%'. $searchText . '%';
        } 
        
        $order = ['Articles.created' => 'DESC'];
        
        if (!empty($options['orderBy'])) {
            if($options['orderBy'] == 'view_count') {
                $order = ['Articles.view_count' => 'ASC'];
            } elseif ($options['orderBy'] == 'featured') {
                $conditions['featured'] =  true;
            }
        }
        
        if (!empty($options['cat'])) {
            $cat = $options['cat'];
            $cCond = ['Categories.slug' => $cat];
            
            if (is_numeric($cat)) {
                $cCond = ['Categories.id' => $cat];
            }
            $query = $query->matching('Categories', function ($q) use ($cCond) {
                    return $q->find('all', ['site'=>'all'])->where($cCond);
                });
        }
        
        if (!empty($options['fields'])) {
          $fields = $fields + $options['fields'];
        }
        
        return $query
                ->where($conditions)
                ->contain(['Categories' => function($q) use ($locale) {
                    return $q->select(['Categories.name' . $locale, 'Categories.slug']);
                }])
                ->select($fields)
                ->order($order);
    }
    
    public function incrementViewCount($id){
        $number = 1;//rand(1, 5);
        $expression = new QueryExpression('view_count = view_count + '.$number);
        $this->updateAll($expression, ['id'=>$id]);
    }
    
    public function countByCategory(Query $query, $cat_slug = null){
        $totalCount = 0;
        $totalViewCount = 0;
        $categories = [];
        $selectedCategoryId = null;
        foreach($query as $article){
            $totalCount ++;
            $totalViewCount +=$article->view_count;
            if(empty($article->categories)) continue;
            foreach($article->categories as $category){
                if(!isset($categories[$category->id])){
                    $categories[$category->id] = $category;
                    $categories[$category->id]->count = 1;
                }else{
                    $categories[$category->id]->count++;
                }
                if($category->slug ==$cat_slug){
                    $selectedCategoryId = $category->id;
                }
            }
        }
        return [$totalCount, $categories, $selectedCategoryId, $totalViewCount];
    }
    
    public function findDetail(Query $query, array $options){
        $locale = (isset($options['lang']) && $options['lang'] == 'mn') ? '' : '_en';
        $slug = $options['slug'];
        $conditions = ['Articles.slug' => $slug];
        if (is_numeric($slug)) {
            $conditions = ['Articles.id' => $slug];
        }
        
        if (empty($options['preview'])) {
            $conditions = $conditions + ['Articles.published' => true];
        }
        
        $fields = ['Articles.id', 'Articles.title', 'Articles.cover_image', 'Articles.slug', 'Articles.featured', 'Articles.created', 'Articles.view_count', 'Articles.html'];
        return $query->where($conditions)
                ->contain(['Categories' => function($q) use ($locale) {
                    return $q->select(['Categories.name' . $locale, 'Categories.slug'])->order(['lft' > 'ASC']);
                }])
                ->select($fields);
    }
    
    public function findSearch(Query $query, array $options){
        $searchValue = $options['search'];
        $conditions = [
            'Articles.published' => true,
            'Articles.language' => $options['lang'],
            'OR'=>[
                'Articles.title LIKE' => '%' . $searchValue . '%',
                'Articles.html LIKE' => '%' . $searchValue . '%',
            ]
            
        ];
        
        return $query->where($conditions)
        ->order(['Articles.created' => 'DESC'])
        ->select(['Articles.id', 'Articles.title', 'Articles.slug', 'Articles.sub_title', 'Articles.html']);
    }
}