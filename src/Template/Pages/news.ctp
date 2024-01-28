<?php 
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

$totalPages = convertTotalPageStringToNumber($this->Paginator->counter('{{pages}}'));
?>

<div class="blue-background">
    <div class="uk-container uk-container-center">
        <div class="search">
            <div class="uk-hidden-small uk-vertical-align uk-clearfix">
                <?= $this->Form->create('', ['type' => 'get', 'class' => 'uk-form search-form uk-float-left']) ?>
                <input id="core-search" type="search" name="searchText" placeholder="<?= __("Хайх") ?>"
                    value="<?= isset($searchText) ? $searchText : '' ?>" class="transition">
                <button type="submit" class="uk-button uk-button-link uk-icon-search"></button>
                <?php echo $this->Form->end(); ?>
                <span class="search-key-word uk-margin-left uk-vertical-center uk-float-left">Түлхүүр үг:<span>
                <?php foreach($hashtags as $hashtag) : ?>
                  <span class="hashtag">#<?= $hashtag->name?></span>
                <?php endforeach; ?>
              </div>
        </div>
        <div class="top-news uk-text-center">
            <?= $this->Html->image($featuredNews->cover_image); ?>
            <div class="uk-grid small-info">
                <div class="uk-width-1-5 uk-hidden-small">
                </div>
                <div class="uk-width-1-1 uk-width-medium-3-5 info uk-text-center">
                    <h1 class="title"><?= getTrimmedSentences($featuredNews->title, 60); ?></h1>
                    <p class="text"><?= getTrimmedSentences($featuredNews->sub_title, 170); ?></p>
                    <p class="date"> <?= getNewsDateString($featuredNews->created); ?>&nbsp&nbsp•&nbsp&nbsp<?= $featuredNews->calculateReadMinute(); ?> <?= __('мин')?><p>
                </div>
                <div class="uk-width-1-5">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="uk-container uk-container-center">
  <div id="articles" class="uk-grid news uk-hidden-small">
    <?php if(!empty($news)) :?>
        <?= $this->element('articles/article', ['news' => $news]) ?>
    <?php else :?>
      <div class="uk-width-1-1 uk-text-center margin-top-120 margin-bottom-120">
          <p class="about-dark-blue-title"><?= __('Хайлтад тохирсон мэдээ олдсонгүй.'); ?></p>
      </div>
    <?php endif;?>
  </div>
  <div class="uk-grid news-small uk-visible-small">
      <?php if(!empty($news)) :?>
        <?= $this->element('articles/article_mobile', ['news' => $news]) ?>
    <?php else :?>
      <div class="uk-width-1-1 uk-text-center margin-top-120 margin-bottom-120">
          <p class="about-dark-blue-title"><?= __('Хайлтад тохирсон мэдээ олдсонгүй.'); ?></p>
      </div>
    <?php endif;?>
  </div>
  <?php if(!empty($news)) :?>
    <div class="uk-grid">
      <a rel="next" onclick="showMore(this)" data-container-id="articles" data-page="1" data-totalpage="<?= $totalPages ?>" data-loading="false" class="uk-width-1-1 uk-text-center margin-top-120 margin-bottom-120">
            <p class="about-dark-blue-title"><?= __('Цааш унших'); ?></p>
              <?= $this->Html->image('icons/news-next.png', ['class' => "margin-top-20"]); ?>
        </a>
    </div>
  <?php endif;?>
</div>
<?php
$url = $this->Url->build(["controller" => "Pages", "action" => "ajaxArticles"]);;
if(count($this->request->query) > 0){
    $url .= '&';
}else{
    $url .= '?';
}
$url .= 'page=';
echo $this->element('articles/article_paginate_js', ['url'=> $url]);
?>
