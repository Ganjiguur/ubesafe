<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Utility\Text;
use Cake\Utility\Inflector;

/**
 * AppView helper
 */
class AppViewHelper extends Helper {

    public $helpers = ['Html', 'Paginator'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getLang() {
        if (I18n::locale() == "en") {
            return "en";
        }
        return "mn";
    }

    public function getMultSelectLabel($for, $title) {
        $el = "label";
        if (strlen($title) < 26) {
            return '<' . $el . ' for="' . $for . '">' . $title . '</' . $el . '>';
        }
        
        return '<' . $el . ' for="' . $for . '">' . Text::truncate($title, 22, ['ellipsis' => ' ']) . '</' . $el . '><i title="' . $title . '" data-uk-tooltip="{cls:\'long-text\'}" class="material-icons">more_horiz</i>';
    }

    public function getSiteMeta($name) {
        if (Configure::check($name . "-" . $this->getLang())) {
            return Configure::read($name . "-" . $this->getLang());
        }
        return Configure::read($name);
    }
    
    public function getStatusHeader($status) {
        return $this->Paginator->sort($status, '<i class="material-icons" title="' . __("Order by") . ' ' . __('Status') . '" data-uk-tooltip="{cls:\'long-text\'}">fiber_manual_record</i>', ['escape' => false]);
    }
    
    public function getStatusIndicator($status, $emptyIfActive = true) {
        if (!$status) {
            return '<i class="material-icons uk-text-danger" title="' . __("Идэвхгүй") . '" data-uk-tooltip>fiber_manual_record</i>';
        }
        if(!$emptyIfActive) {
          return '<i class="material-icons uk-text-success" title="' . __("Идэвхтэй") . '" data-uk-tooltip>fiber_manual_record</i>';
        }
        
    }
    
    public function getFullBaseUrl() {
      $baseUrl = Configure::read('App.fullBaseUrl');
      if(strstr($baseUrl, '//localhost')) {
        $baseUrl .= '/aminhariutslaga';
      }
      return $baseUrl;
    }
    
    /**
     * Add reCaptcha script
     * @return void
     */
    public function addInvisibleReCaptchaScript()
    {
        if (!Configure::read('Users.reCaptcha.key')) {
            return $this->Html->tag('p', __('reCaptcha is not configured! Please configure Users.reCaptcha.key'));
        }

        $this->Html->script('https://www.google.com/recaptcha/api.js?onload=onLoadInvisibleRecaptcha&render=explicit&hl=mn', [
            'block' => 'script',
            'async'=>'',
            'defer'=>''
        ]);
        

        $this->Html->scriptStart(['block' => true]);
        echo 'var onLoadInvisibleRecaptcha = function(){
            $( ".invisible-recaptcha" ).each(function() {
                var btn = $(this);
                grecaptcha.render(this, {
                    \'sitekey\' : \''.Configure::read('Users.reCaptcha.key').'\',
                    \'callback\' : function(token) {
                        btn.parents("form").find(".g-recaptcha-response").val(token);
                        btn.parents("form").submit();
                    },
                    \'badge\' : \'inline\',
                });
            });
        }';
        $this->Html->scriptEnd();
    }
}
