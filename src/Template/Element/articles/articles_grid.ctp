<?php if (!empty($articles)): ?>
<div class="uk-grid uk-grid-medium" data-uk-grid-margin data-uk-grid-match="{target:'.product-card'}" style="position: relative;z-index: 1;">
    <?php 
    foreach ($articles as $item) {
      echo $this->element('articles/article', ['article' => $item]);
    }
    ?>
  </div>
<?= $this->element('site/pagination') ?>
<?php endif; ?>