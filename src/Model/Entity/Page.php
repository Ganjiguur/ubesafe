<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Batu\Version\Model\Behavior\Version\VersionTrait;

require_once(ROOT . DS . 'plugins' . DS . "tools.php");

/**
* Page Entity.
*
* @property int $id
* @property string $slug
* @property string $name
* @property string $title
* @property string $description
* @property string $updated_by
* @property string $html
* @property int $status
* @property string $language
* @property string $article_categories
* @property \Cake\I18n\Time $created
* @property \Cake\I18n\Time $modified
*/
class Page extends Entity {
    
    use VersionTrait;
    
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
    
    protected function _setSlug($slug) {
        return Inflector::slug(mb_strtolower($slug));
    }
    
    protected function _getCategoryIds($value) {
        if (isset($value)) {
            return $value;
        }
        // for multiple selection
        //        if (!$this->article_categories) {
        //            return [];
        //        }
        //
        //        return explode(',', $this->article_categories);
        
        // for single selection
        return $this->article_categories;
    }
    
    protected function _setCategoryIds($value) {
        // for multiple selection
        //$this->set('article_categories', implode(',', $value));
        
        // for single selection
        if($value == '') {
            $this->set('article_categories', null);
        } else {
            $this->set('article_categories', $value);
        }
        
        return $value;
    }
    
    protected function _getTemplates(){
        return [
            "" => __("Default"), 
            "about" => __("Төслийн тухай"),
            "home" => __("Нүүр хуудас"),
            "intro" => __("Заавар"),
            "news" => __("Мэдээ")
        ];
    }

    protected function _getLanguages(){
        return getLanguages();
    }
    
    protected function _getLabel() {
      return $this->_properties['name'] . ' (' . $this->_properties['site'] . ' )';
    }
    
    //Update menu link which is linked to this page.
    public function updateMenu($oldSlug) {
        $menus = TableRegistry::get('Menus');
        $menus->updateAll(['link' => $this->slug], ['link_type' => 'page', 'link' => $oldSlug, 'language' => $this->language]);
    }
    
    function truncateTitle($length){
        return Text::truncate($this->title, $length, ['ellipsis' => '...', 'exact' => false]);
    }
    
    protected function _getTitleForPage(){
        return Text::truncate($this->title, 100, ['ellipsis' => '...', 'exact' => false]);
    }
    
    function editUrl() {
        return Router::url(['plugin'=>null,'controller' => 'Pages', 'action' => 'edit', $this->id], true);
    }
    
    function _getPreviewUrl() {
      return Router::url(['plugin'=>null,'controller' => 'Pages', 'action' => 'view', 'preview' => true, $this->slug], true);
    }
    
    protected function _getUrl() {
      return Router::url(['plugin'=>null,'controller' => 'Pages', 'action' => 'view', $this->slug], true);
    }
}