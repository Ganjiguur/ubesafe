<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Routing\Router;

/**
 * Category Entity
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property string $slug
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property int $level
 * @property string $description
 * @property int $position
 * @property string $icon
 * @property bool $active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\ParentCategory $parent_category
 * @property \App\Model\Entity\ChildCategory[] $child_categories
 * @property \App\Model\Entity\Article[] $articles
 */
class Category extends Entity
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
        'id' => false
    ];
    
    protected function _setSlug($slug) {
        return Inflector::slug(mb_strtolower($slug));
    }
    
    protected function _getNameLocale($name) {
        if(I18n::locale() == "en") {
            return $this->_properties["name_en"];
        } else {
            return $this->_properties["name"];
        }
    }
    
    protected function _getRoute(){
        return ['controller' => 'articles', 'action' => 'category', $this->slug];
    }
    
    //Update menu link which is linked to this page.
    public function updateMenu($oldSlug) {
        $menus = TableRegistry::get('Menus');
        $menus->updateAll(['link' => $this->slug], ['link_type' => 'category', 'link' => $oldSlug]);
    }
    
    protected function _getUrl() {
      //TODO Fix link
        return Router::url($this->route, true);
    }
    
    
}
