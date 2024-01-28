<?php

$sideHtml = "";
$pageTabs = [];
if (!empty($page->tabs)) {
  foreach ($page->tabs as $ind => $tab) {
    if (mb_strtolower($tab['name']) == 'side') {
      $sideHtml = $tab['content'];
    } else {
      $pageTabs[] = $tab;
    }
  }
}

$full_banner = null;

if (!empty($page->banners)) {
  foreach ($page->banners as $banner) {
    if($banner->type === "pagecover_wide") {
      $full_banner = $banner;
      break;
    }
  }
}
?>

<?= $this->element('site/crumb', ['crumbs' => $crumblist, 'pageTitle' => $page->title]); ?>

  <?php if($full_banner !== null): ?>
<?php /*  <div class="uk-cover" style="height: 300px">
    <video class="uk-cover-object about-video-object" autoplay="" muted="" loop="" width="" height="300">
      <source src="<?= $this->Url->build('/files/video/about.mp4') ?>" type="video/mp4">
    </video>
  </div> */?>

  <div class="cover uk-cover-background" style="background-image:url('<?= $full_banner->media ?>');height: 300px">
    <?= $this->Html->image($full_banner->media, ['class' => 'uk-width-1-1 uk-invisible']) ?>
  </div>
<?php endif; ?>

<div class="maincontent paddingbottom-remove">

  <div class="uk-container uk-container-center">
    <div class="maincontent_detail">
      <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
        <div class="uk-width ck-pagecontent">
          <?= $this->element('pages/content', ['contentTabs' => $pageTabs]) ?>
        </div>

        <div class="uk-width ck-sidebar">
          <?= $this->element('site/sidemenu', ['menuitems' => $sideMenus]) ?>

          <?= $sideHtml ?>

          <?= $this->element('site/side_links') ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->element('site/footer_top', ['banners' => $page->banners]) ?>
<!--Bottom right screen popup banner-->
<?php
if (!empty($page->banners)) {
  foreach ($page->banners as $banner) {
    switch ($banner->type) {
        case 'popup_footer':
          echo $this->element('site/banner_side', ['banner' => $banner]);
          break;
        case 'popup_footer_left':
          echo $this->element('site/banner_side_left', ['banner' => $banner]);
          break;
        case 'popup_footer_center':
          echo $this->element('site/banner_side_center', ['banner' => $banner]);
          break;
        default:
          break;
      }
  }
}
?>
