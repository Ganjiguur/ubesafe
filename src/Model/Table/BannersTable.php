<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \Cake\I18n\Date;
use Cake\Database\Expression\QueryExpression;

/**
 * Banners Model
 *
 * @method \App\Model\Entity\Banner get($primaryKey, $options = [])
 * @method \App\Model\Entity\Banner newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Banner[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Banner|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Banner[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Banner findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BannersTable extends Table
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

        $this->setTable('banners');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('FileManager');
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['title', 'active'],
            'onlyDirty' => true
        ]);
        
        $this->hasMany('Locations', [
            'className' => 'ModelLocations',
            'foreignKey' => 'model_id',
            'conditions' => ['Locations.model' => 'banners'],
            'dependent' => true
        ]);
        
        $this->belongsToMany('Pages', [
            'foreignKey' => 'model_id',
            'targetForeignKey' => 'foreign_key',
            'joinTable' => 'model_locations',
            'conditions' => ['ModelLocations.model' => 'banners', 'ModelLocations.foreign_class' => 'pages']
        ]);
        
        $this->belongsToMany('Products', [
            'foreignKey' => 'model_id',
            'targetForeignKey' => 'foreign_key',
            'joinTable' => 'model_locations',
            'conditions' => ['ModelLocations.model' => 'banners', 'ModelLocations.foreign_class' => 'products']
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
            ->allowEmpty('title');

        $validator
            ->allowEmpty('title_en');

        $validator
            ->allowEmpty('subtitle');

        $validator
            ->allowEmpty('subtitle_en');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->allowEmpty('link');

        $validator
            ->allowEmpty('linktext');

        $validator
            ->allowEmpty('linktext_en');

        $validator
            ->boolean('link_newtab')
            ->allowEmpty('link_newtab');

        $validator
            ->allowEmpty('media');

        $validator
            ->allowEmpty('media_type');

        $validator
            ->boolean('active')
            ->allowEmpty('active');
        
        $validator
            ->boolean('homepage')
            ->allowEmpty('homepage');

        $validator
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmpty('position');

        $validator
            ->dateTime('startdate')
            ->allowEmpty('startdate');

        $validator
            ->dateTime('enddate')
            ->allowEmpty('enddate');

        $validator
            ->boolean('never_end')
            ->allowEmpty('never_end');

        $validator
            ->allowEmpty('site');

        $validator
            ->allowEmpty('language');

        return $validator;
    }
    
    public function incrementClickCount($id){
        $number = 1;
        $expression = new QueryExpression('click_counter = click_counter + '.$number);
        $this->updateAll($expression, ['id'=>$id]);
    }
    
    public function findForHome(Query $query, array $options){
        $lang = (isset($options['lang']) && $options['lang'] == 'en') ? 'en' : 'mn';
        $locale = $lang == 'en' ? '_en' : '';
        $fields = ['type', 'title'.$locale, 'subtitle'.$locale, 'media_type', 'media', 'link', 'linktext'.$locale, 'link_newtab'];
        $date = new Date();
        return $query
            ->select($fields)
            ->where([
                'language IN' => ['all', $lang],
                'homepage' => true,
                'active' => true,
                'OR' => [
                    ['never_end' => true],
                    ['startdate <=' => $date, 'enddate >=' => $date], 
                ]
            ])
            ->order(['position'=>'ASC']);
    }
    
    public function findByType(Query $query, array $options){
        $lang = (isset($options['lang']) && $options['lang'] == 'en') ? 'en' : 'mn';
        $locale = $lang == 'en' ? '_en' : '';
        $fields = ['type', 'title'.$locale, 'subtitle'.$locale, 'media_type', 'media', 'link', 'linktext'.$locale, 'link_newtab','position'];
        $date = new Date();
        $conditions = [
                'language IN' => ['all', $lang],
                'active' => true,
                'OR' => [
                    ['never_end' => true],
                    ['startdate <=' => $date, 'enddate >=' => $date], 
                ]
            ];
        if (!empty($options['type'])) {
            $conditions['type'] = $options['type'];
        }
        
        return $query
            ->select($fields)
            ->where($conditions)
            ->order(['position'=>'ASC']);
    }
    
    public function afterDelete($event, $entity) {
        if(in_array($entity->media_type, ['image']) &&  !empty($entity->media)){
            $this->deleteFile($entity->media);
        }
    }
    
    public function findForJoin(Query $query, array $options){
      $lang = (isset($options['lang']) && $options['lang'] == 'en') ? 'en' : 'mn';
      $locale = $lang == 'en' ? '_en' : '';
      $date = new Date();
      return $query->find('all',['site' => 'all'])
              ->select(['id', 'type', 'title'.$locale, 'subtitle'.$locale, 'call_to_action'.$locale, 'media_type', 'media', 'link', 'linktext'.$locale, 'link_newtab', 'delay', 'frequency', 'ModelLocations.foreign_key'])
              ->where([
                  'language IN' => ['all', $lang],
                  'active' => true,
                  'OR' => [
                      ['never_end' => true],
                      ['startdate <=' => $date, 'enddate >=' => $date], 
                  ]
              ])
              ->order(['position'=>'ASC']);
    }
}
