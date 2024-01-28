<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Admin component
 */
class AdminComponent extends Component
{
    protected $prefix = "admin";
    protected $controller;
    
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();
    }
    
    public function beforeRender(Event $event) {
        //Adding admin prefix to the template name
        if ($this->controller->viewBuilder()->layout() === null && $this->controller->viewBuilder()->template() === null) {
            $template = $this->prefix . '_' . Inflector::underscore($this->request->action);
            $this->controller->viewBuilder()->setTemplate($template);
        }
    }
    
    /**
     * CombineAssociations method 2017.04.1
     * 
     * Enehuu function bichih bolson shaltgaan:
     * 
     * 1. Cake ORM ashiglan olon table holboj bolj baisan ch database ruu shaardlagagui 
     * mash olon dawhardsan request yawuulj baisan tul uuniig bolomjit hamgiin baga 
     * request ashiglan guitsetgeh zorilgoor
     * 
     * 2. Mun entity hadgalah burd joined table deerh umnu baisan relation uudiig 
     * bugdiig ustgaad shineer hadgalj baigaa ni ur ashigguin deer row id het ih 
     * bolj usuh muu taltai baisan
     * 
     * 3. Ene turliin holboltuudiig olon table uud deer ashiglah shaardlagatai baisan tul 
     * dundaa 1 join table share hiideg baih uudnees bichsen
     * 
     * Zoriulalt: belongsToMany relations iig `model`, 'foreign_class' talbar ashiglan 
     * olon table iig holbohod ashiglaj bgaa tohioldold daraah function iig ashiglana
     * 
     * Towch: belongsToMany data (form data) orj irehed tedgeeriig negtgen joined 
     * table deer ni hasMany relation data uusgen oruulj ugch bgaa yum.
     * Ingehdee $main entity uurtuu buh related row uudaa aguulsan baina gej uzeh 
     * buguud orj irsen data tai tedgeeriig haritsuulan shineer nemegdsen 
     * relationuudiig tsaash hadgalahaar yawuulna. Harin umnu baisnaas hasagdsan 
     * relation uudiig $deleteFromTo huwisagchid tsugluulan butsaaj bgaa yum
     * 
     * @param Entity $main 
     * @param array $associations belongsToMany relation uudiin ners
     * @param type $to joined table iin ner (hasMany relation name)
     * @return array hasMany relation ees ustgah row uudiin id nuud
     */
    public function combineAssociations(Entity $main, Array $associations, $to) {
        $this->request->data[$to] = [];
        $existingLinks = [];
        $deleteFromTo = [];
        foreach ($associations as $assName) {
            //collect existing related data from entity object
            $existingLinks[$assName] = [];
            $assLinks = empty($main[$assName]) ? [] : $main[$assName];
            foreach ($assLinks as $ass) {
                $existingLinks[$assName][$ass->id] = $ass->_joinData->id;
            }            
            
            //combining to hasMany data
            $_ids = empty($this->request->data[$assName]['_ids']) ? [] : (array)$this->request->data[$assName]['_ids'];
            unset($this->request->data[$assName]);
            
            foreach ($_ids as $_id) {
                if(isset($existingLinks[$assName][$_id])) {
                    unset($existingLinks[$assName][$_id]);
                } else {
                    $this->request->data[$to][] = [
                        'foreign_key' => $_id, 
                        'foreign_class' => $assName, 
                        'model' => Inflector::tableize($main->getSource())
                    ];
                }
            }
            
            // collecting rows to delete
            foreach ($existingLinks[$assName] as $id) {
                $deleteFromTo[] = $id;
            }
        }
        
        return $deleteFromTo;
    }
    
    //Recover tree structure of the model
    public function checkTreeRecover() {
      if ($this->request->query('recover') == 'true') {
        $model = TableRegistry::get($this->controller->modelClass);
        $model->behaviors()->Tree->config('recoverOrder', 'lft');
        $model->recover();
        $queryParams = $this->request->query;
        $link = $this->request->pass;
        $queryParams['recover'] = null;
        $link['?'] = $queryParams;
        return $this->controller->redirect($link);
      }
    }
    
    
}
