<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * IMPORTANT:
 * This is an example configuration file. Copy this file into your config directory and edit to
 * setup your app permissions.
 *
 * This is a quick roles-permissions implementation
 * Rules are evaluated top-down, first matching rule will apply
 * Each line define
 *      [
 *          'role' => 'role' | ['roles'] | '*'
 *          'prefix' => 'Prefix' | , (default = null)
 *          'plugin' => 'Plugin' | , (default = null)
 *          'controller' => 'Controller' | ['Controllers'] | '*',
 *          'action' => 'action' | ['actions'] | '*',
 *          'allowed' => true | false | callback (default = true)
 *      ]
 * You could use '*' to match anything
 * 'allowed' will be considered true if not defined. It allows a callable to manage complex
 * permissions, like this
 * 'allowed' => function (array $user, $role, Request $request) {}
 *
 * Example, using allowed callable to define permissions only for the owner of the Posts to edit/delete
 *
 * (remember to add the 'uses' at the top of the permissions.php file for Hash, TableRegistry and Request
   [
        'role' => ['user'],
        'controller' => ['Posts'],
        'action' => ['edit', 'delete'],
        'allowed' => function(array $user, $role, Request $request) {
            $postId = Hash::get($request->params, 'pass.0');
            $post = TableRegistry::get('Posts')->get($postId);
            $userId = Hash::get($user, 'id');
            if (!empty($post->user_id) && !empty($userId)) {
                return $post->user_id === $userId;
            }
            return false;
        }
    ],
 */

use CakeDC\Users\Auth\Rules\Owner;
use Cake\Network\Request;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

return [
    'Users.SimpleRbac.permissions' => [
        /*** Must use our AppUsers controller actions. ***/
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => '*',
            'action' => '*',
            'allowed' => false
        ],
        /***  admin role allowed to use everything  ***/
        [
            'role' => 'admin',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
        ],
        /***  Custom permissions for chinggis khaan bank ***/      
        [
            'role' => '*',
            'controller' => 'Extends',
            'action' => ['ajaxUploadImage', 'ajaxUploadFile', 'ajaxDeleteImage', 'ajaxLoadImages']
        ],
        [
            'role' => '*',
            'controller' => 'Version',
            'action' => ['revisions', 'restore']
        ],
        [
            'role' => '*',
            'controller' => 'AppUsers',
            'action' => ['edit', 'logout'],
        ],
        //Allow dashboard
        [
            'role' => '*',
            'controller' => 'Dashboard',
            'action' => ['index'],
        ],
        
        /***  Controller Based Authorization ***/
        [
            'role' => 'moderator',
            'controller' => '*',
            'action' => '*',
            'allowed' => function (array $user, $role, Request $request) {
                return in_array($request->controller, $user['permission']);
            }
        ],
        
        /***  Other action specific extra permissions  ***/
        [
            'role' => 'moderator',
            'controller' => ['Articles'],
            'action' => ['edit','delete','preview'],
            'allowed' => new \CakeDC\Auth\Rbac\Rules\Owner()
        ],
        [
            'role' => 'moderator',
            'controller' => ['Articles'],
            'action' => ['ajaxSaveArticle'],
            'allowed' => function(array $user, $role, Request $request){
                $articleId = Hash::get($request->data, 'article_id');
                $article = TableRegistry::get('Articles')->get($articleId);
                $userId = Hash::get($user, 'id');
                if (!empty($article->user_id) && !empty($userId)) {
                    return $article->user_id === $userId;
                }
                return false;
            }
        ],
        
                
    
                
        /***  MUST BE PLACED IN THE END ***/   
        /***  Disable all other actions which are not covered in above ***/   
                
        [
            'role' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
            'allowed' => false
        ],
       
    ]];
