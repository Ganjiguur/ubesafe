<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Application Entity
 *
 * @property int $id
 * @property string $relativeWorkHere
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 */
class Application extends Entity
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
    
    protected function _getGenderLocale() {
        if($this->gender == "0") {
            return "Эмэгтэй";
        }
        return "Эрэгтэй";
    }
    
    protected function _getCurrentAddressTypeLocale() {
        switch ($this->currentAddressType) {
            case 'own': return 'Өөрийн';
            case 'relatives': return 'Эцэг, эх, хамаатан садангийн';
            case 'rent': return 'Түрээслэдэг';
            default: return '-';
        }
    }
    
//    protected function _getEducationLevelLocale() {
//        switch ($this->educationLevel) {
//            case 'Дээд': return 'Дээд';
//            case 'Тусгай дунд': return 'Тусгай дунд';
//            case 'Бүрэн дунд': return 'Бүрэн дунд';
//            case 'Бүрэн бус дунд': return 'Бүрэн бус дунд';
//            case 'Бусад': return 'Бусад';
//            default: return '-';
//        }
//    }
    
    protected function _getMarriedLocale() {
        switch ($this->married) {
            case 'single': return 'Үгүй';
            case 'married': return 'Тийм';
            case 'divorsed': return 'Салсан';
            default: return '-';
        }
    }
    
    public function boolVal($prop) {
        if(empty($this->_properties[$prop])) {
            return "Үгүй";
        }
        return "Тйим";
    }
    
    protected function _getSchoolTypes() {
      return [
          "" =>  "Сонгоно уу",
          "secondary" =>  "Дунд сургууль",
          "university" =>  "Их, дээд сургууль",
          "other" =>  "Бусад",
      ];
    }
    
    public function skillLocale($val) {
        switch ($val) {
            case 1: return __("Муу");
            case 2: return __("Дунд");
            case 3: return __("Сайн");
            default: return '-';
        }
    }
    
    public function knowledgeLocale($val) {
        switch ($val) {
            case 1: return __("Анхан шат");
            case 2: return __("Хэрэглээний түвшинд");
            case 3: return __("Бүрэн эзэмшсэн");
            default: return '-';
        }
    }
    
  protected function _getInfoSources() {
    return [
        "school" => "Сургууль дээрээсээ",
        "newspaper" => "Сонин дээрх зараас",
        "friends" => "Найз, төрөл садангаас",
        "direct" => "Банктай холбоо барьж, эсвэл өөрөө ирж",
        "other" => "Бусад: /дээр дурьдаагүй ямар мэдээллийн сувгаар/",
    ];
  }
    
  protected function _getDefaultApps() {
    return ['MS Word', 'MS Excel', 'MS PowerPoint'];
  }
}
