<?php

require_once(ROOT . DS . 'plugins' . DS . "timeAgoInWords.php");

use Cake\Core\Configure;
use Cake\Utility\Text;
use Cake\I18n\I18n;

function getWeekName($thisDate) {
    $day = date('w', strtotime($thisDate));
    switch ($day) {
        case '1': return "Даваа";
        case '2': return "Мягмар";
        case '3': return "Лхагва";
        case '4': return "Пүрэв";
        case '5': return "Баасан";
        case '6': return "Бямба";
        case '0': return "Ням";
        default: return "";
    }
}

function firstSentence($string, $length = null) {
    $sentences = explode(". ", $string);
    if ($length !== null) {
        if (mb_strlen($sentences[0]) > $length) {
            return Text::truncate($sentences[0], $length, [
                        'ellipsis' => '...',
                        'exact' => false
                            ]
            );
        } else {
            return $sentences[0] . '.';
        }
    } else {
        return $sentences[0] . '.';
    }
}

function getSentencesInLength($string, $length) {
    $sentences = explode(". ", $string);
    $text = $sentences[0];
    for ($i = 1; $i < count($sentences); $i++) {
        if (mb_strlen($text . '. ' . $sentences[$i]) > $length) {
            if (mb_strlen($text) > $length) {
                return Text::truncate($text, $length, [
                            'ellipsis' => '...',
                            'exact' => false
                                ]
                );
            } else {
                if (mb_strlen($text) < $length * 0.5) {
                    return Text::truncate($text . '. ' . $sentences[$i], $length, [
                                'ellipsis' => '...',
                                'exact' => false
                                    ]
                    );
                } else {
                    return $text . '.';
                }
            }
        }
        if (!empty($sentences[$i])) {
            $text .= '. ' . $sentences[$i];
        }
    }
    return $string;
}

function getTrimmedSentences($string, $length) {
    return mb_strimwidth($string, 0, $length, "...");
}


function getDateString($date, $dateOnly = false) {
    if($date == null) {
        return ' - ';
    }
    if(I18n::locale() == "mn") {
        $format = "Y/m/d";
    } else {
        $format = "d M, Y";
    }
    if(!$dateOnly) {
        $format .= " H:i";
    }
    return date_format($date, $format);
}


function getNewsDateString($date) {
    if($date == null) {
        return ' - ';
    }
    if(I18n::locale() == "mn") {
        $format = "m сарын d ";
    } else {
        $format = "d M, Y";
    }
    return date_format($date, $format);
} 

function getImages($html) {
    if(preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $html, $matches, PREG_PATTERN_ORDER)){
        return $matches[1];	
    }
    return array();
}

function getFirstVideo($html) {
    if(preg_match_all('/<iframe .*src=["|\']([^"|\']+)/i', $html, $matches, PREG_PATTERN_ORDER)){
        return $matches[1][0];	
    }
//    if(preg_match_all('/<embed .* src=["|\']([^"|\']+)/i', $html, $matches, PREG_PATTERN_ORDER)){
//        return $matches[1];	
//    }
    return null;
}

function getVideos($html) {
    if(preg_match_all('/<iframe .*src=["|\']([^"|\']+)/i', $html, $matches, PREG_PATTERN_ORDER)){
        return $matches[1];	
    }
    return null;
}

function convertISOdurationToSeconds($iso){
    $seconds = 0;
    preg_match_all('/\d+/', $iso, $parts);
    $parts = $parts[0];
    $h = strpos($iso,'H');
    $m = strpos($iso,'M');
    $s = strpos($iso,'S');

    if($m && !$h && !$s){
        $parts= [0, $parts[0], 0];
    }
    if($h && !$m){
        $parts= [$parts[0], 0, $parts[1]];
    }
    if($h && !$m && !$s){
        $parts = [$parts[0], 0, 0];
    }

    if(count($parts) ==3){
        $seconds += (int)$parts[0] * 3600;
        $seconds += (int)$parts[1] * 60;
        $seconds += (int)$parts[2];
    }
    if(count($parts) ==2){
        $seconds += (int)$parts[0] * 60;
        $seconds += (int)$parts[1];
    }
    if(count($parts) ==1){
        $seconds += (int)$parts[0];
    }
    return $seconds;
}

function getYouTubeIdFromURL($url) {
    if (empty($url)) {
        return "";
    }
    if (strlen($url) >= 11 && strlen($url) < 15) {
        return $url;
    }
    $pattern = '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+|(?<=embed/)[^&\n]+(?=\?)#';
    preg_match($pattern, $url, $matches);
    if (count($matches) == 0) {
        return "";
    }
    if (strstr($matches[0], "?", true)) {
        return strstr($matches[0], "?", true);
    }
    if (strstr($matches[0], "/", true)) {
        return strstr($matches[0], "/", true);
    }
    return $matches[0];
}

function getFullUrl($url) {
    if (0 === strpos($url, 'http://') || 0 === strpos($url, 'https://') || 0 === strpos($url, '#')) {
        // It starts with 'http' or '#'
        return $url;
    }
    return 'http://'.$url;
}

function str_replace_n($search, $replace, $subject, $occurrence)
{
	$search = preg_quote($search);
  return preg_replace("/^((?:(?:.*?$search){".--$occurrence."}.*?))$search/", "$1$replace", $subject);
}

function wrapString($text, $width = null) {
    $width = $width == null ? ceil(mb_strlen($text) / 2) + 3 : $width;
    return Text::wrap($text, [
        'width' => $width,
        'indent' => '<br>',
        'indentAt' => 1
    ]);
}

function into2Line($text) {
  $spaces = mb_substr_count($text, " ");
  if($spaces == 0) { return $text; }
  $occurence = ceil($spaces / 2);
        
  return str_replace_n(' ', '<br>', $text, $occurence);
}

function getRoles(){
    return [
        'admin'=>['text'=>'Админ'],
//        'publisher' => ['text'=>'Нийтлэгч'],
        'moderator'=>['text'=>'Модератор']
    ];
}

function getLanguages($element = 'emptyValue') {
    $data = ["mn" => __('Монгол'), "en" => __("English")];
    if ($element === 'emptyValue') {
        return $data;
    }

    if (is_string($element) && isset($data[$element])) {
        return $data[$element];
    } else {
        return '';
    }
}

function defaultLocale() {
    return Configure::read('rw_locale');
}

function getSystemLangSuffix() {
    $locale = I18n::locale();
    return getLangSuffix($locale);
}

function getLangSuffix($lang = null) {
    $default = defaultLocale();
    $langs = getLanguages();
    
    if(empty($lang) || !isset($langs[$lang])) {
        $lang = $default;
    }

    if($lang == $default) {
        return '';
    }
    return '_' . $lang;
}

function adminModules() {
    $data = [
        "AppUsers" => [
            'icon'=> 'person', 
            'title'=> 'Хэрэглэгчид', 
            'action'=> ["plugin"=>null,'controller'=>'AppUsers','action'=>'index'],
        ],
        "Extends" => [
            'icon'=> 'settings', 
            'title'=> 'Сайтын мэдээлэл', 
            'action'=> ["plugin"=>null,'controller'=>'Extends','action'=>'siteMeta'],
        ],
        "Categories" => [
            'icon'=> 'label', 
            'title'=> 'Категорууд', 
            'action'=> ["plugin"=>null,'controller'=>'Categories','action'=>'index'],
        ]
    ];
    return $data;
}

function availableModules() {
    $data = [
        "Articles" => [
            'icon'=> 'description', 
            'title'=> 'Нийтлэлүүд', 
            'action'=> ["plugin"=>null,'controller'=>'Articles','action'=>'index'],
        ],
        "Pages" => [
            'icon'=> 'pages', 
            'title'=> 'Вэб хуудсууд', 
            'action'=> ["plugin"=>null,'controller'=>'Pages','action'=>'adminIndex'],
        ],
        "Banners" => [
            'icon'=> 'chrome_reader_mode', 
            'title'=> 'Banners', 
            'action'=> ["plugin"=>null,'controller'=>'Banners','action'=>'index'],
        ],
    ];
    return $data;
}

function getModules() {
    $data = [];
    foreach (availableModules() as $ctrl => $item) {
        $data[$ctrl] = $item['title'];
    }
    return $data;
}

function getUikitCssPath($name) {
    return "/cms/css/$name";
//    return "https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/css/$name.css";
}

function getActiveIds($tree, $activeId) {
  $activeIds = [];
  $activeIds[] = $activeId;
  foreach ($tree as $item) {  
    if($activeId == $item->id) {
      break;
    }
    $hasChild = count($item->children) > 0;
    if($hasChild) {
      $childActive = false;
      foreach ($item->children as $child) {
        if($activeId == $child->id) {
          $childActive = true;
          break;
        }
        if(count($child->children) > 0) {
          $grandActive = false;
          foreach ($child->children as $grand) {
            if($activeId == $grand->id) {
              $grandActive = true;
              break;
            }
          }
          if($grandActive) {
            $activeIds[] = $child->id;
          }
        }
      }
      if($childActive) {
        $activeIds[] = $item->id;
      }
    }
  }
  return $activeIds;
}

function formatRate($rate, $abs = false) {
  if (is_numeric($rate)) {
    if ($abs) {
      $rate = abs($rate);
    }
    
    $rate = number_format($rate, 2);
  }
  
  return $rate;
}

function getPhoneLinks($phone, $clazz = "") {
  $phones = explode(',', $phone);
  $html = "";
  $comma = '';
    foreach ($phones as $number) {
      $_number = trim($number);
      if(!empty($_number)) {
        $html .= $comma . '<a href="tel:' . $_number . '" class="' . $clazz . '">' . $_number . '</a>';
      }
      $comma = ', ';
    }
    return $html;
}

function getMonthFormat() {
    if(I18n::locale() == "mn") {
        return "M сар";
    } else {
        return "MMM";
    }
}

function getSocials() {
  return ['facebook', 'twitter',  'instagram','youtube', 'linkedin'];
}

function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
    
    return 'Other';
}

function convertTotalPageStringToNumber($totalPages) {
    if(!is_numeric($totalPages)){
        return str_replace(",", "",$totalPages);
    }
    return $totalPages;
}