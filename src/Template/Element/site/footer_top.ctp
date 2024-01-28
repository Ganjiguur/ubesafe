<?php 
$hasFooterBanner = false;
if(!empty($banners)): ?>
  <?php foreach ($banners as $banner) : ?>
    <?php if($banner->type == 'footer_banner'): ?>
      <div class="banner_bottom uk-cover-background" style="background-image:url('<?= $banner->media ?>')">
        <div class="uk-container uk-container-center uk-position-relative uk-height-1-1">
          <?= $banner->subtitleLocale ?>
          <?php /*
          <?= $this->Html->image($banner['image'], ['class' => 'overlay_image']) ?>
          <div class="uk-text-right uk-position-bottom-right">
            <h1 class="text-darkblue"><?= $banner['title'] ?></h1>
            <p class="text-16 text-darkblue"><?= $banner['text'] ?></p>
            <p class="margin-top-40">
              <a href="<?= $banner['link'] ?>" class="bluebutton"><?= $banner['linkText'] ?>
                <i class="uk-icon-chevron-circle-right"></i>
              </a>
            </p>
          </div>*/?>
        </div>
      </div>
    <?php
      $hasFooterBanner = true;
      break; 
    endif; 
    ?>
  <?php endforeach; ?>
<?php endif; ?>

<?php if(!$hasFooterBanner): ?>

  <?php if(isset($product)): ?>
    <div class="uk-container uk-container-center">
      <?= $this->element('site/services', ['_style' => 'margin-top: 44px;margin-bottom: 44px;']) ?>
    </div>
  <?php else: ?>
    <div class="uk-container uk-container-center">
      <?= $this->element('site/page_tools', ['_style' => 'margin-top: 44px;margin-bottom: 44px;']) ?>
    </div>
  <?php endif; ?>
  
<?php endif; ?>