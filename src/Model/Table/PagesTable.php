<?php
namespace App\Model\Table;

use App\Model\Entity\Page;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \Cake\I18n\Date;
use Cake\Database\Schema\Table as Schema;

/**
 * Pages Model
 *
 */
class PagesTable extends Table
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

        $this->table('pages');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['name', 'title', 'html', 'archived', 'status', 'slug', 'tabs'],
            'archivedField' => 'archived',
            'onlyDirty' => true
        ]);
        
        $this->belongsTo('Users', [
            'className' => 'AppUsers',
            'foreignKey' => 'updated_by'
        ]);
        
        $this->belongsToMany('Banners', [
            'foreignKey' => 'foreign_key',
            'targetForeignKey' => 'model_id',
            'joinTable' => 'model_locations',
            'conditions' => ['ModelLocations.model' => 'banners', 'ModelLocations.foreign_class' => 'pages']
        ]);
    }
    
    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('tabs', 'json');
        return $table;
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
            ->notEmpty('slug');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('description');

        $validator
            ->allowEmpty('updated_by');

        $validator
            ->allowEmpty('html');
        
        $validator
            ->allowEmpty('tabs');

        $validator
                ->add('status', 'valid', ['rule' => 'numeric'])
                ->requirePresence('status', 'create')
                ->allowEmpty('status');

        $validator
            ->allowEmpty('language');

        $validator
            ->allowEmpty('article_categories');

        return $validator;
    }
    
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['slug', 'language']), '_isUnique', [
            'errorField' => 'slug',
            'message' => __('Вэб хуудсын линк давхардаж байна!')
        ]);
        return $rules;
    }
    
    public function findForJoin(Query $query, array $options){
        return $query->where(['Pages.archived' => false, 'Pages.status' => true]);
    }
    
    public function findDetail(Query $query, array $options){
        $lang = (isset($options['lang']) && $options['lang'] == 'en') ? 'en' : 'mn';
        $locale = $lang == 'en' ? '_en' : '';
        $date = new Date();
        return $query->where(['Pages.language'=>$lang])
            ->contain([
                'Banners' => function (Query $query) use ($lang) {
                    return $query->find('forJoin', ['lang' => $lang]);
                }
            ]);
    }
}
