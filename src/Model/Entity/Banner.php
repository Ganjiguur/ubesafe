<?php
namespace App\Model\Entity;

require_once(ROOT . DS . 'plugins' . DS . "tools.php");

use Cake\ORM\Entity;
use \Cake\I18n\Date;
use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * Banner Entity
 *
 * @property int $id
 * @property string $title
 * @property string $title_en
 * @property string $subtitle
 * @property string $subtitle_en
 * @property string $type
 * @property string $link
 * @property string $linktext
 * @property string $linktext_en
 * @property bool $link_newtab
 * @property string $media
 * @property string $media_type
 * @property bool $active
 * @property bool $homepage
 * @property int $position
 * @property \Cake\I18n\Time $startdate
 * @property \Cake\I18n\Time $enddate
 * @property bool $never_end
 * @property string $site
 * @property string $language
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Banner extends Entity
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

    protected function _getTypes(){
        return [
//            "popup" => __("Popup (Center screen)"),
            "popup_footer" => __("Popup right (Bottom screen)"),
            "popup_footer_left" => __("Popup left (Bottom screen)"),
            "popup_footer_center" => __("Popup center (Bottom screen)"),
            "homecover" => __("Homepage cover"),
//            "home_service" => __("Homepage service"),
//            "online_service" => __("Online service"),
            "suggested_service" => __("Suggested service"),
            "footer_banner" => __("Footer top banner"),
            "pagecover" => __("Webpage cover"),
            "pagecover_wide" => __("Webpage cover (wide)"),
        ];
    }

    public function homeTypes() {
        return ['homecover', 'homefeatured'];
    }

    protected function _getMediaTypes(){
        return [
            "image" => __("Homepage cover"),
            "swf" => __("Homepage featured"),
            "youtube" => __("Webpage cover")
        ];
    }

    protected function _getTypeLocale(){
        return $this->types[$this->type];
    }

    protected function _getTitleLocale() {
        return $this->locale("title");
    }

    protected function _getSubtitleLocale() {
        return $this->locale("subtitle");
    }

    protected function _getLinktextLocale() {
        return $this->locale("linktext");
    }

    protected function _getCallToActionLocale() {
        return $this->locale("call_to_action");
    }

    protected function _getLanguages(){
        return getLanguages() + ['all' => __("Бүх хэл дээр харагдна")];
    }

    protected function _getIsActive(){
        if (!$this->active) {
            return false;
        }
        if ($this->never_end) {
            return true;
        }

        if ($this->startdate == null || $this->enddate == null) {
            return false;
        }

        return ($this->startdate->isPast() || $this->startdate->isToday()) && ($this->enddate->isFuture() || $this->enddate->isToday());
    }


    public function _getSiteLink() {
        return $this->link;
    }

    function locale($field) {
        if(I18n::locale() == "en" && isset($this->_properties[$field . "_en"])) {
            return $this->_properties[$field . "_en"];
        }
        return $this->_properties[$field];
    }
}
