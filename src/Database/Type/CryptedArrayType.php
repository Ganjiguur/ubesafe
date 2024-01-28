<?php
namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\Utility\Security;

class CryptedArrayType extends Type
{
    public function toDatabase($value, Driver $driver)
    {
        if(!empty($value) && is_array($value)){
            $value = implode(",", $value);
        } else {
            $value = "";
        }
        return Security::encrypt($value, Security::salt());
    }

    public function toPHP($value, Driver $driver)
    {
        if (!empty($value)) {
            $decrypted = Security::decrypt($value, Security::salt());
            if($decrypted) {
                return explode(",", $decrypted);
            }
        }
        
        return [];
    }
}