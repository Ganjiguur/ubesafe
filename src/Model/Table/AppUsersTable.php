<?php

namespace App\Model\Table;

use CakeDC\Users\Model\Table\UsersTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use Cake\Database\Schema\Table as Schema;
use DateTime;

/**
 * Users Model
 */
class AppUsersTable extends UsersTable {
    
    /**
     * Role Constants
     */
    const ROLE_USER = 'moderator';
    const ROLE_ADMIN = 'admin';

    public function initialize(array $config) {
        parent::initialize($config);
        
        $this->addBehavior('Batu/Version.Version', [
            'extraGroupFields' => ['user_id', 'user_name', 'action', 'created'],
            'fields' => ['username', 'email', 'active'],
            'onlyDirty' => true
        ]);

        $this->hasMany('Articles', [
            'foreignKey' => 'user_id'
        ]);
    }
    
    protected function _initializeSchema(Schema $table)
    {
         $table->columnType('permission', 'json');
        return $table;
    }
    
    public function validationPasswordConfirm(Validator $validator)
    {
        $validator
            ->requirePresence('password_confirm', 'create')
            ->notEmpty('password_confirm');

        $validator->add('password', 'custom', [
            'rule' => function ($value, $context) {
                $confirm = Hash::get($context, 'data.password_confirm');
                if (!is_null($confirm) && $value != $confirm) {
                    return false;
                }
                return true;
            },
            'message' => __('Таны нууц үг хоорондоо таарахгүй байна. Дахин оруулна уу'),
            'on' => ['create', 'update'],
            'allowEmpty' => false
        ]);
            
        $validator->add('password', 'length', [ 
            'rule' => ['lengthBetween', 8, 100],
            'message' => __('Таны нууц үг 8 тэмдэгтээс багагүй урттай байх ёстой.')
        ]);
        
        $validator->add('password', 'custom2', [
            'rule' => function ($value) {
                $l = mb_strlen($value);
                $upper = false;
                $lower = false;
                $number = false;
                $special = false;
                for ($i = 0; $i < $l; $i++){
                    $ch = $value[$i]; 
                    if(is_numeric($ch)) {
                        $number = true;
                    } else {
                      if(in_array($ch, ['!','@','#','$','%','&','*','(',')','-','_','+','=','[',']','{','}','`', '~', '"', "'", ';', ':', '/', '<', '>', '.', ',', '\\', '|', '^', '?'])) {
                        $special = true;
                      } elseif($ch === mb_strtolower($ch)) {
                          $lower = true;
                      } else {
                          $upper = true;
                      } 
                    }
                }
                if($upper && $lower && $number && $special) {
                    return true;
                }
                return false;
            },
            'message' => __('Таны нууц үг том жижиг үсэг, тоо, тусгай тэмдэгт агуулсан байх ёстой.')
        ]);

        return $validator;
    }
    
    public function validationEmailConfirm(Validator $validator)
    {
        $validator->add('email', 'valid-email', [
                'rule' => 'email',
                'message' => __('Имэйл хаяг алдаатай байна.')
                ])
                ->notEmpty('email');
        
        $validator->add('email', 'valid-email', [
            'rule' => function ($value, $context) {
                return $this->find()->where(['email' => $value])->all()->isEmpty();
            },
            'message' => __('Имэйл хаяг бүртгэлтэй байна')
        ]);
        
        return $validator;
    }
    
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');
        
        $validator->add('password', 'length', [
            'rule' => ['lengthBetween', 8, 100],
            'message' => __('Таны нууц үг 8 тэмдэгтээс багагүй урттай байх ёстой.')
        ]);
        
        $validator->add('password', 'custom', [
            'rule' => function ($value) {
                $l = mb_strlen($value);
                $upper = false;
                $lower = false;
                $number = false;
                $special = false;
                for ($i = 0; $i < $l; $i++){
                    $ch = $value[$i]; 
                    if(is_numeric($ch)) {
                        $number = true;
                    } else {
                      if(in_array($ch, ['!','@','#','$','%','&','*','(',')','-','_','+','=','[',']','{','}','`', '~', '"', "'", ';', ':', '/', '<', '>', '.', ',', '\\', '|', '^', '?'])) {
                        $special = true;
                      } elseif($ch === mb_strtolower($ch)) {
                          $lower = true;
                      } else {
                          $upper = true;
                      } 
                    }
                }
                if($upper && $lower && $number && $special) {
                    return true;
                }
                return false;
            },
            'message' => __('Таны нууц үг том жижиг үсэг, тоо, тусгай тэмдэгт агуулсан байх ёстой.')
        ]);
        
        $validator
            //->requirePresence('full_name', 'create')
            ->allowEmpty('full_name');

        $validator
            ->allowEmpty('token');
        
        $validator
            ->allowEmpty('website');
        
        $validator
            ->allowEmpty('bio');

        $validator
            ->add('token_expires', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('token_expires');

        $validator
            ->allowEmpty('api_token');

        $validator
            ->add('activation_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('activation_date');

        $validator
            ->add('tos_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('tos_date');

        return $validator;
    }
    
    public function validationRegister(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator = $this->validationPasswordConfirm($validator);
        return $validator;
    }
    
    public function validationRegisterStart(Validator $validator)
    {
        $validator = $this->validationEmailConfirm($validator);
        $validator = $this->validationPasswordConfirm($validator);
        return $validator;
    }
    
    public function buildRules(RulesChecker $rules)
    {
        //Commented because we use email address for login and username is same as email
//        $rules->add($rules->isUnique(['username']), '_isUnique', [
//            'errorField' => 'username',
//            'message' => __d('Users', 'Username already exists')
//        ]);

        //if ($this->isValidateEmail) {
            $rules->add($rules->isUnique(['email']), '_isUnique', [
                'errorField' => 'email',
                'message' => __('Имэйл хаяг бүртгэлтэй байна')
            ]);
        //}

        return $rules;
    }
    
    public function changeRole($username, $role)
    {
        $user = $this->find()->where(['AppUsers.username' => $username])->first();
        if (empty($user)) {
            return false;
        }
        if($role == 'superuser') {
            return false;
        }
        $user->role = $role;
        $savedUser = $this->save($user);
        return $savedUser;
    }
    
    public function activateUser(EntityInterface $user)
    {
        if ($user->active) {
            throw new UserAlreadyActiveException(__d('Users', "User account already validated"));
        }
        $user->activation_date = new DateTime();
        $user->token_expires = null;
        $user->active = true;
        $result = $this->save($user);

        return $result;
    }

    public function findMostWritten(Query $query, array $options){
        return $query->where(['is_superuser'=>false])->contain(['SocialAccounts'])->order(['article_count'=>'DESC'])->limit(10);
    }
}
