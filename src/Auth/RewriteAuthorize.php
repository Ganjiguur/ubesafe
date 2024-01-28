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

namespace App\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;

/**
 * Superuser Authorize
 *
 * Detect and give full access to superusers
 *
 */
class RewriteAuthorize extends BaseAuthorize
{
    /**
     * default config
     *
     * @var array
     */
    protected $_defaultConfig = [
        //superuser field in the Users table
        //'superuser_field' => 'is_superuser',
    ];

    /**
     * Check if the user is superuser
     *
     * @param type $user User information object.
     * @param Request $request Cake request object.
     * @return bool
     */
    public function authorize($user, Request $request)
    {
        if($user['role'] == 'user'){
            $userPermission = explode(',', $user['permission']);
            if( count($userPermission) == 0){
                return false;
            }
            if(in_array($request->params['controller'], $userPermission)) {
                return true;
            }
        }
        return false;
    }
}
