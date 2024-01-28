<?php 
use Cake\Utility\Inflector;

echo $this->Html->css("jquery.scrollbar", ['block' => 'top']);
echo $this->Html->script("jquery.scrollbar.min", ['block' => 'top']);

$coverBanner = null;
$hasFooterBanner = false;
if (!empty($page->banners)) {
  foreach ($page->banners as $banner) {    
    if($banner->type == "pagecover" && !$coverBanner) {
      $coverBanner = $banner;
    }
    if($banner->type == 'footer_banner') {
      $hasFooterBanner = true;
    }
  }
}

$hasArticles = false;
if(!empty($articles)) {
  $hasArticles = true;
}

$hasForm = false;
if(!empty($page->forms)) {
  $hasForm = h($page->forms[0]->title);
}
?>

<style>
  .inner-nav>.scrollbar-macosx {
    max-width: 100%;
  }
</style>

<?php if($coverBanner !== null): ?>
<div class="cover uk-cover-background" style="background-image:url('<?= $coverBanner->media ?>')">
  <?= $this->Html->image($coverBanner->media, ['class' => 'uk-width-1-1 uk-invisible']) ?>
</div>
<?php endif; ?>

<?php if(!empty($page->html) || !empty($contentTabs) || !empty($page->forms)): ?>

<div class="maintext fr-view <?= count($contentTabs) ? 'no-padding-top' : '' ?> <?= $hasFooterBanner ? 'has-footer-banner' : '' ?> <?= $hasArticles ? 'has-articles' : '' ?>"> 

  <?php if(!count($contentTabs)): ?>
    <?= $page->html ?>
  <?php else: 
    $hasMainHtml = false;
    if(!empty($page->html)) {
      $hasMainHtml = true;
    }
  ?>

    <div class="inner-nav uk-hidden-small" data-uk-sticky="{media: 640}">
      <div class="scrollbar-macosx">
        <?php foreach ($contentTabs as $ind => $tab) : ?>
          <a href="#<?= Inflector::slug(mb_strtolower(strip_tags($tab['name'])), '_') ?>" data-uk-smooth-scroll="{offset: 70}" class="nav-item <?= ($ind == 0 && !$hasMainHtml) ? 'active' : '' ?>"><?= $tab['name'] ?></a>
        <?php endforeach; ?>
        <?php if ($hasForm != false): ?>
          <a href="#rw_form" data-uk-smooth-scroll="{offset: 70}" class="nav-item"><?= $hasForm ?></a>
        <?php endif; ?>
      </div>
    </div>

    <?php if($hasMainHtml): ?>
      <?= $page->html ?>
      <br>
    <?php endif; ?>

    <?php foreach ($contentTabs as $ind => $tab) : ?>
      <?= $ind == 0 ? '' : '<br>' ?>
      <h4 id="<?= Inflector::slug(mb_strtolower(strip_tags($tab['name'])), '_') ?>" class="ck-title inner-item"><?= $tab['name'] ?></h4>
      <?= $tab['content'] ?>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($hasForm != false): ?>
    <?php if(!empty($page->html) || count($contentTabs) > 0): ?>
      <?= '<br><hr><br>' ?>
    <?php endif; ?>
    <?php 
      echo $this->element('site/form', [
            'form' => $page->forms[0], 
            'formClass' => 'uk-form', 
            'btnClass' => 'uk-button uk-button-small yellowbutton', 
            'inputClass' => 'uk-width-1-1 ck-input small',
            'gridClass' => 'uk-grid uk-grid-medium',
            'titleClass' => 'uk-text-uppercase text-16 text-grape uk-margin-top-remove inner-item',
            'showTitle' => true,
            'showLabel' => true,
            'useGrid' => true,
            'showBtnArrow' => true,
            ]);
      ?>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if($hasArticles): ?>
  <div class="">
      <?= $this->element('articles/articles_grid', ['articles' => $articles]); ?>
    </div>
<?php endif; ?>

<?php if (false) : ?>
<script>
<?php else: ?>
<?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>    
  $(function() {
    innerNavScroll();
    
    $(".inner-nav a.nav-item").on('mouseenter', function() {
      scrollToNavItem($(this));
    });
  });
<?php if (false) : ?>
</script>
<?php else: ?>
    <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>