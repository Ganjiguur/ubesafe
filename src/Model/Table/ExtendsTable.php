<?php

namespace App\Model\Table;

use App\Model\Entity\Extend;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Log\Log;

/**
 * Extends Model
 *
 */
class ExtendsTable extends Table {

    /**
     * Path to settings file
     *
     * @var string
     */
    public $settingsPath = '';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('extends');
        $this->displayField('label');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('FileManager');
//      
//        $this->addBehavior('Batu/Version.Version', [
//            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
//            'fields' => ['unique_key', 'data'],
//            'onlyDirty' => true
//        ]);

        $this->settingsPath = APP . 'Config' . DS . 'settings.json';
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
                ->allowEmpty('editable');

        $validator
                ->requirePresence('type', 'create')
                ->notEmpty('type');

        $validator
                ->allowEmpty('field');

        $validator
                ->requirePresence('unique_key', 'create')
                ->notEmpty('unique_key');

        $validator
                ->requirePresence('label', 'create')
                ->notEmpty('label');

        $validator
                ->allowEmpty('data');

        $validator
                ->allowEmpty('site');

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
        $rules->add($rules->isUnique(['unique_key']));
        return $rules;
    }

    /**
     * afterSave callback
     *
     * @return void
     */
    public function afterSave(Event $event, Entity $entity, $options) {
        if(isset($entity->editable) && $entity->editable == 0) {
            return;
        }
        $this->updateSettings();
        $this->writeConfiguration();
    }

    /**
     * afterDelete callback
     *
     * @return void
     */
    public function afterDelete(Event $event, Entity $entity, $options) {
        if(isset($entity->type) && $entity->type == "image" && !empty($entity->data)){
            $this->deleteFile($entity->data);
        }
        
        if(isset($entity->editable) && $entity->editable == 0) {
            return;
        }
        $this->updateSettings();
        $this->writeConfiguration();
    }

    /**
     * All key/value pairs are made accessible from Configure class
     *
     * @return void
     */
    public function writeConfiguration() {
        Configure::load('settings', 'settings');
    }

    /**
     * Find list and save yaml dump in app/Config/settings.json file.
     * Data required in bootstrap.
     *
     * @return void
     */
    public function updateSettings() {
        $settings = $this->find('all')
                ->where(['editable'=>true])
                ->select(['unique_key', 'data'])
                ->order(['unique_key' => 'ASC'])
                ->toArray();

        $settings = array_combine(
                Hash::extract($settings, '{n}.unique_key'), Hash::extract($settings, '{n}.data')
        );
        
        if(empty($settings)) {
            $settings = ['empty' => 'empty'];
        }

        $result = Configure::write($settings);
        if(!$result) {
          Log::debug("**************** Settings write error ****************");
          return;
        }
        $keys = [];
        foreach ($settings as $key => $setting) {
            //list($key, $ignore) = explode('.', $key, 2);
            $keys[] = $key;
        }
        $keys = array_unique($keys);
        if(empty($keys)|| empty($settings)) {
          Log::debug("**************** Settings info empty to write to configuration file ****************");
          return;
        }
        
        Log::debug($keys);
        
        Configure::dump('settings', 'settings', $keys);
    }

}
