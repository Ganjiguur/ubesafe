<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;

class FileManagerBehavior extends Behavior
{
    protected $_defaultConfig = [
        'fieldsToDelete' => null
    ];
    
    protected static function get_server_var($id) {
        return @$_SERVER[$id];
    }
    
    public function deleteFile($fileUrl) {

        if (empty($fileUrl)) {
            return false;
        }

        //remove get parameters if exists
        $url = strtok($fileUrl, '?');
        //$filename = basename($url);
        $dir = strstr($url, "/files/");
        
        $resource = dirname($this->get_server_var('SCRIPT_FILENAME')) . $dir;
        file_exists($resource) and unlink($resource);

        return true;
    }
    
    // Delete files automatically when a row deleted
    public function afterDelete($event, $entity) {
        $fileFields = (array)$this->_config['fieldsToDelete'];
        if(!empty($fileFields)){
            foreach ($fileFields as $field) {
                $fileUrl = $entity->get($field);
                if (!empty($fileUrl)) {
                    $this->deleteFile($fileUrl);
                }
            }
        }
    }
}