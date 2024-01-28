<?php

namespace App\View\Cell;

use Cake\View\Cell;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;

/**
 * Mneu cell
 */
class ExtendCell extends Cell {

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display() {
//        if (I18n::locale() == "en") {
//            $menu_lang = "en";
//        } else {
//            $menu_lang = "mn";
//        }
//        $this->loadModel('Menus');
//        $menus = $this->Menus->find('all')
//                ->where(['language' => $menu_lang, 'active' => true])
//                ->order(['lft' => 'ASC'])
//                ->find('threaded')
//                ->toArray();
//
//        $this->set('footermenu', $menus);

    }
    
    public function totalRequests() {
      $FormRecords = TableRegistry::get('FormRecords');
      $this->set('totalRequests', $FormRecords->find('all', ['site' => 'all'])->where(['action_by IS NULL'])->count());
    }
}
