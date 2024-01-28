<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\Routing\Router;
use Cake\I18n\I18n;
use Cake\Core\Configure;
use Batu\Version\Model\Behavior\Version\VersionTrait;

require_once(ROOT . DS . 'plugins' . DS . "timeAgoInWords.php");
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

/**
* Article Entity.
*
* @property int $id
* @property string $title
* @property string $slug
* @property string $description
* @property string $html
* @property int $user_id
* @property \App\Model\Entity\User $user
* @property bool $published
* @property bool $featured
* @property int $view
* @property bool $comment_status
* @property int $comments
* @property \Cake\I18n\Time $created
* @property \Cake\I18n\Time $modified
* @property \App\Model\Entity\Category[] $categories
*/

//TODO See all default images


class Article extends Entity {
    
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
    
    protected function _getSquareImage() {
        if (!empty($this->cover_image)) {
          $ext = pathinfo($this->cover_image, PATHINFO_EXTENSION);
            return rtrim($this->cover_image, '.' . $ext) . '_square' . '.' . $ext;
        }
        return Router::url('/img/news_blank.jpg', true);
    }
    
    protected function _getCover() {
        if (!empty($this->cover_image)) {
          return $this->cover_image;
        }
        return "";
    }
    
    protected function _getShortHtml(){
        return Text::truncate(strip_tags($this->html), 100, ['ellipsis' => '...','exact' => false, 'html' => true]);
    }
    
    protected function _getCreatedAgo(){
        return timeAgoInWords($this->created);
    }
    
    protected function _getWordCount(){
        $words = preg_split('/\s+/', strip_tags($this->html));
        return count($words);
    }
    
    protected function _getCreatedFull(){
        return date_format($this->created, I18n::locale() == "mn"?"Y.m.d":"d M, Y");
    }
    
    
    protected function _getUrl(){
        return Router::url(['controller' => 'article', 'action' => $this->slug], true);
    }
    
    protected function _getLink(){
        return '<a href='.$this->url.'>'.$this->title . '</a>';
    }
    protected function _getSubLink(){
        return '<a href='.$this->url.'>'.$this->sub_title . '</a>';
    }
    
    protected function _getLanguages(){
        return getLanguages();
    }
    
    protected function _getCategoryName(){
        return empty($this->categories[0])?'':h($this->categories[0]->name_locale);
    }
    
    protected function _getTitleForPage(){
        return Text::truncate($this->title, 100, ['ellipsis' => '...', 'exact' => false]);
    }
    protected function _getUserPhotoUrl(){
        if(!empty($this->user->photo)){
            return $this->user->photo;
        }
        if(!empty($this->user->avatar)){
            return $this->user->avatar;
        }
        return Configure::read('Users.Avatar.placeholder');
    }
    
    protected function _getReadMinute($value){
        if($value)return $value;
        return $this->calculateReadMinute();
    }

    public function calculateReadMinute(){
        $minute = ceil($this->word_count/150);
        $videos = getVideos($this->html);
        if($videos) {
            $youtubeVideoIds = [];
            foreach($videos as $video){
                $id = getYouTubeIdFromURL($video);
                if($id){
                    $youtubeVideoIds[] = $id;
                    continue;
                }
                
                $id = getVimeoIdFromUrl($video);
                if($id){
                    $duration = getVimeoVideoLength($id);
                    $minute += ceil($duration / 60);
                    continue;
                }
                
                $id = getSoundCloudIdFromUrl($video);
                if($id){
                    $duration = getSoundCloudTrackLength($id);
                    $minute += ceil($duration / 60);
                }
            }
            if(count($youtubeVideoIds)){
                $minute += ceil(getYoutubeVideoLength(join(",", $youtubeVideoIds))/60);
            }
            
        }
        $this->set('read_minute', $minute);
        return $minute;
    }

    function truncateTitle($length){
        return Text::truncate($this->title, $length, ['ellipsis' => '...', 'exact' => false]);
    }
    
    function editUrl() {
        return Router::url(['plugin'=>null,'controller' => 'articles', 'action' => 'edit', $this->id], true);
    }
    
    function _getPreviewUrl() {
      return Router::url(['plugin'=>null,'controller' => 'articles', 'action' => 'view', 'preview' => true, $this->slug], true);
    }
}
