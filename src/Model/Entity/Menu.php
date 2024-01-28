<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

/**
* Menu Entity.
*
* @property int $id
* @property string $name
* @property int $parent_id
* @property \App\Model\Entity\Menu $parent_menu
* @property string $link
* @property string $attr
* @property int $lft
* @property int $rght
* @property int $active
* @property string $language
* @property int $position
* @property \Cake\I18n\Time $created
* @property \Cake\I18n\Time $modified
* @property \App\Model\Entity\Menu[] $child_menus
*/
class Menu extends Entity
{
    
    /**
    * Fields that can be mass assigned using newEntity() or patchEntity().
    *
    * Note that when '*' is set to true, this allows all unspecified fields to
    * be mass assigned. For security purposes, it is advised to set '*' to false
    * (or remove it), and explicitly make individual fields accessible as needed.
    *
    * @var array
    */
    protected $_accessible = [
    '*' => true,
    'id' => false,
    ];
    
    protected function _getAttributes(){
        $attributes = ['escapeTitle' => false];
        if (!$this->attr) {
            return $attributes;
        }
        
        $attrs = explode(',', $this->attr);
        foreach($attrs as $attr){
            $keyValue = explode('=', $attr);
            if(count($keyValue)!=2) continue;
            $keyValue[1]= str_replace("'",'',$keyValue[1]);
            $keyValue[1]= str_replace('"','',$keyValue[1]);
            $attributes[$keyValue[0]] = $keyValue[1];
        }
        return $attributes;
    }
    
    protected function _getLinkTypes(){
        return [
            "blank" => __("Линкгүй"), 
            "page" => __("Вэб хуудас"), 
            "category" => __("Категори"), 
            "product" => __("Бүтээгдэхүүн"), 
            "url" => __("Вэб линк")
          ];
    }
    
    protected function _getUrl() {
        return Router::url($this->route, true);
    }
    
    protected function _getNameLocale() {
        return $this->_properties["name"];
    }
    
    protected function _getIsOnlyOnFooter() {
      return $this->show_on_footer && !$this->show_on_mobile && !$this->show_on_mainmenu;
    }
    
    protected function _getRoute(){
        $param = !empty($this->link) ? $this->link : null;
        if($this->link_type == 'page') {
            return ['controller' => 'pages', 'action' => 'view', $param];
        }
        if($this->link_type == 'category') {
            return ['controller' => 'articles', 'action' => 'category', $param];
        }
        if($this->link_type == 'product') {
            return ['controller' => 'products', 'action' => 'view', $param];
        }
        if(!empty($this->link)){
            return getFullUrl(h($this->link));
        }
        return '#';
    }

}