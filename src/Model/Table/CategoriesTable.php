<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;

require_once ROOT . DS . 'plugins' . DS . "tools.php";

/**
 * Categories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentCategories
 * @property \Cake\ORM\Association\HasMany $ChildCategories
 * @property \Cake\ORM\Association\BelongsToMany $Articles
 *
 * @method \App\Model\Entity\Category get($primaryKey, $options = [])
 * @method \App\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Category findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CategoriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('categories');
        $this->displayField('name_locale');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', ['level' => 'level']);
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['name', 'active'],
            'onlyDirty' => true
        ]);

        $this->belongsTo('ParentCategories', [
            'className' => 'Categories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildCategories', [
            'className' => 'Categories',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsToMany('Articles', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'article_id',
            'joinTable' => 'categories_articles'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
                ->add('name', 'notBlank', ['rule' => 'notBlank', 'message' => __('Төрлийн нэр хоосон байна.')])
                ->requirePresence('name', 'create');
        $validator
                ->add('name_en', 'notBlank', ['rule' => 'notBlank', 'message' => __('Төрлийн англи нэр хоосон байна.')])
                ->requirePresence('name_en', 'create');

        $validator
            ->add('slug', 'notBlank', ['rule' => 'notBlank', 'message' => __('Талбарыг гүйцэт бөглөнө үү.')])
            ->requirePresence('slug', 'create');
        
        $validator
            ->allowEmpty('description');

        $validator
            ->allowEmpty('icon');

        $validator
            ->boolean('active')
            ->allowEmpty('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['slug']), '_isUnique', [
            'errorField' => 'slug',
            'message' => __('Категорийн линк давхардаж байна!')
        ]);
        $rules->add($rules->existsIn('parent_id', 'ParentCategories', ['allowNullableNulls' => true]));
        return $rules;
    }
    
    public function findForSite(Query $query, array $options) {
        return $query;
    }
    
    public function beforeSave(Event $event, EntityInterface $entity, $options)
    {
        if(isset($options['removeTree'])) {
          
          if($this->behaviors()->has('Tree')) {
            $this->removeBehavior('Tree');
          }
        }
    }
}
