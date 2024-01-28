<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ExtendComponent extends Component {
    
    public $controller;
    public $weatherAndRateViews;
    
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();
        $this->weatherAndRateViews = [];
        $this->getCategories = [];
        if (isset($config['weatherAndRateViews'])) {
            $this->weatherAndRateViews = $config['weatherAndRateViews'];
        }
        if (isset($config['getCategories'])) {
            $this->getCategories = $config['getCategories'];
        }
    }
    
    public function beforeRender(Event $event) {
        try {
            //setting currency and weather information
            if (in_array($this->controller->request->action, $this->weatherAndRateViews)) {
                $currency = $this->getCurrencyData();
                $this->controller->set('currencyRate', json_decode($currency['data']));
                $this->controller->set('weatherInfo', $this->getWeatherData());
                
                $currentTime =  strtotime(date('Y-m-d H:i:s' ,time()));
                $modifiedTime =  strtotime($currency['modified']);
                
                if(($currentTime - $modifiedTime) > 60 * 60 * 1){ //one hour
                    $this->controller->set('updateWeatherCurrency' , true);
                } else {
                    $this->controller->set('updateWeatherCurrency', false);
                }
            }
            
            if (in_array($this->controller->request->action, $this->getCategories)) {
                $cat = TableRegistry::get('Categories');
                $this->controller->set('categories',$cat->find('all')
                ->order(['lft'=>'ASC'])
                ->toArray());
            }
        } catch (BlackHoleException $exception) {
            return $this->controller->blackHole($exception->getMessage());
        } catch (NoActionException $exception) {
            
        }
    }
    
    public function getCurrencyData() {
        
        $extends = TableRegistry::get('Extends');
        $queryCurrencyRate = $extends->find()
        ->where(['type' => 'currency_json'])
        ->first();
        return  $queryCurrencyRate;
    }
    
    public function getWeatherData() {
        
        $extends = TableRegistry::get('Extends');
        $queryWeatherInfo =$extends->find()
        ->where(['type' => 'weather_json'])
        ->select(['Extends.data'])
        ->first();
        return  json_decode($queryWeatherInfo['data']);
    }
}