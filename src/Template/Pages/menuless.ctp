<?= $this->element('site/crumb', ['crumbs' => $crumblist, 'pageTitle' => $page->title]); ?>

<div class="maincontent paddingbottom-remove">
  <div class="uk-container uk-container-center">
    <div class="maincontent_detail">
      <?= $this->element('pages/content') ?>
    </div>
  </div>
</div>

<?= $this->element('site/footer_top', ['banners' => $page->banners]) ?>