<?php
if(!empty($news)){
  $totalPages = convertTotalPageStringToNumber($this->Paginator->counter('{{pages}}'));
}

?>
<?php if(!empty($news)) :?>
<?php foreach($news as $item) :?>
<a class="uk-width-1-1 news-item uk-clearfix" href="<?= $this->Html->Url->build('/article/' . $item->slug)?>">
  <div class="uk-float-left">
  <?= $this->Html->image('news-bg.png', ['class' => 'uk-cover-background', 'alt' => '""', 'style' => 'background-image:url(' . $item->cover_image  . ')']); ?>
  </div>
  <div class="uk-grid uk-float-right info right">
    <div class="uk-width-4-5 info-text">  
      <div class="uk-margin-top">
        <?php foreach($item->categories as $hashtag) :?>
          <span class="hashtag">#<?= $hashtag->name; ?></span>
        <?php endforeach; ?>
      </div>
      <h1 class="title">
          <?= getTrimmedSentences($item->title, 66); ?>
      </h1>
      <p class="description">
        <?= getTrimmedSentences($item->sub_title, 120); ?>
      </p>
    </div>
    <div class="uk-width-1-5">
      <div class="uk-grid uk-grid-collapse" style="height:100%">
        <div class="uk-width-1-5"></div>
        <div class="uk-width-4-5 next-right uk-vertical-align">
          <?= $this->Html->image('icons/next-blue.png', ['class' => 'uk-vertical-align-middle']); ?>
        </div>
      </div>
    </div>
  </div>  
</a>
<?php endforeach; ?>
<?php endif; ?>