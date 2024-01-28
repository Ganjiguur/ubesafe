<?php
namespace App\Model\Table;

use App\Model\Entity\Menu;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;

/**
 * Menus Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentMenus
 * @property \Cake\ORM\Association\HasMany $ChildMenus
 */
class MenusTable extends Table
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

        $this->table('menus');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', ['level' => 'level']);
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['name', 'active'],
            'onlyDirty' => true
        ]);

        $this->belongsTo('ParentMenus', [
            'className' => 'Menus',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildMenus', [
            'className' => 'Menus',
            'foreignKey' => 'parent_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('link');

        $validator
            ->allowEmpty('attr');
        
        $validator
                ->allowEmpty('active');

        $validator
                ->allowEmpty('site');

        $validator
            ->requirePresence('language', 'create')
            ->notEmpty('language');

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
        $rules->add($rules->existsIn('parent_id', 'ParentMenus', ['allowNullableNulls' => true]));       
        return $rules;
    }
        
    public function beforeSave(Event $event, EntityInterface $entity, $options)
    {
        if(isset($options['removeTree'])) {
          if($this->behaviors()->has('Site')) {
            $this->removeBehavior('Site');
          }
          
          if($this->behaviors()->has('Tree')) {
            $this->removeBehavior('Tree');
          }
        }
    }
}
