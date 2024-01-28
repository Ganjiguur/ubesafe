<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Schema\Table as Schema;

/**
 * Applications Model
 *
 * @method \App\Model\Entity\Application get($primaryKey, $options = [])
 * @method \App\Model\Entity\Application newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Application[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Application|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Application patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Application[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Application findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ApplicationsTable extends Table
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

        $this->setTable('applications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }
    
    protected function _initializeSchema(Schema $table)
    {
      $table->columnType('schools', 'json');
      $table->columnType('courses', 'json');
      $table->columnType('languages', 'json');
      $table->columnType('computerKnowledge', 'json');
      $table->columnType('jobs', 'json');
      $table->columnType('sports', 'json');
      $table->columnType('awards', 'json');
      $table->columnType('familyMembers', 'json');
      $table->columnType('mentions', 'json');
      $table->columnType('familyMembers', 'json');
      $table->columnType('relatives', 'json');
      $table->columnType('relativeWorkHere', 'json');
      $table->columnType('wantedSalary', 'json');
      $table->columnType('possibleAlternativePosition', 'json');
      
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
        ->requirePresence('wantedJob', 'create')
        ->notEmpty('wantedJob');
        
        $validator
        ->requirePresence('lastName', 'create')
        ->notEmpty('lastName');
        
        $validator
        ->requirePresence('firstName', 'create')
        ->notEmpty('firstName');
        
        $validator
        ->requirePresence('gender', 'create')
        ->notEmpty('gender');
        
        $validator
        ->requirePresence('age', 'create')
        ->notEmpty('age');
        
        $validator
        ->requirePresence('birthPlace', 'create')
        ->notEmpty('birthPlace');
        
        $validator
        ->requirePresence('birthDate', 'create')
        ->notEmpty('birthDate');
        
        $validator
        ->requirePresence('nationality', 'create')
        ->notEmpty('nationality');
        
        $validator
        ->requirePresence('registrationNumber', 'create')
        ->notEmpty('registrationNumber');
        
        $validator
        ->requirePresence('currentAddress', 'create')
        ->notEmpty('currentAddress');
        
        $validator
        ->requirePresence('profession', 'create')
        ->notEmpty('profession');
        
        $validator
        ->requirePresence('cellPhone', 'create')
        ->notEmpty('cellPhone');
        
        $validator
        ->requirePresence('homePhone', 'create')
        ->notEmpty('homePhone');
        
        $validator
        ->requirePresence('emergency', 'create')
        ->notEmpty('emergency');
        
        $validator
        ->requirePresence('contactEmail', 'create')
        ->notEmpty('contactEmail');
        
        $validator
        ->requirePresence('schools', 'create')
        ->notEmpty('schools');
        
        $validator
        ->requirePresence('languages', 'create')
        ->notEmpty('languages');
        
        $validator
        ->requirePresence('computerKnowledge', 'create')
        ->notEmpty('computerKnowledge');
        
        $validator
        ->requirePresence('mentions', 'create')
        ->notEmpty('mentions');
        
        $validator
        ->requirePresence('familyMembers', 'create')
        ->notEmpty('familyMembers');
        
        $validator
        ->requirePresence('relatives', 'create')
        ->notEmpty('relatives');
        
        $validator
        ->requirePresence('moreDetails', 'create')
        ->notEmpty('moreDetails');
        
        $validator
        ->requirePresence('wantedSalary', 'create')
        ->notEmpty('wantedSalary');
        
        $validator
        ->requirePresence('possibleEmploymentDate', 'create')
        ->notEmpty('possibleEmploymentDate');

        return $validator;
    }
}
