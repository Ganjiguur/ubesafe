
<div class="maincontent">
  <div class="uk-container uk-container-center">
    <div class="maincontent_detail">
      <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
        <div class="uk-width ck-pagecontent">
          <h1 class="about-dark-blue-title margin-top-60 margin-bottom-20 uk-text-center" style="padding: 0px 50px 0px 50px;"><?= $article->title ?></h1>
          <p class="date uk-text-center"><?= getNewsDateString($article->created, true) ?> &nbsp&nbsp•&nbsp&nbsp <?= $article->readMinute . __(' мин унших') ?></p> 
          <?php if(!empty($article->cover_image)): ?>
            <div class="cover uk-cover-background" style="background-image:url('<?= $article->cover ?>')">
              <?= $this->Html->image($article->cover, ['class' => 'uk-width-1-1 uk-invisible']) ?>
            </div>
          <?php endif; ?>
          <div class="margin-top-20 margin-bottom-40">
          <?php foreach($article->categories as $hashtag) {
              echo '<span class="hashtag">#' .$hashtag->name . '</span> ';
          } ?>  
          </div>
          <div class="uk-grid">
            <div class="uk-medium-1-10 uk-width-medium-1-4"
            ></div>
            <div class="uk-medium-8-10 uk-width-medium-2-4 content">
                   <?= $article->html ?>
            </div>
            <div class="uk-width-1-10 uk-width-medium-1-4">              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>