<?php 
  $hasFaq = false;
  if(isset($page) && !empty($page->faqs)) {
    $hasFaq = $page->faqs[0]->id;
  }
  if(isset($product) && !empty($product->faqs)) {
    $hasFaq = $product->faqs[0]->id;
  }
?>


<?php if(isset($show_calculators)): ?>
  <div class="block2">
    <div class="title text-grape uk-text-uppercase uk-margin-bottom">
      <?= $this->Html->image('icons/calculator.png', ['width'=>"18"]) ?>
      <?= __("Тооцоолуур") ?>
    </div>
    <div class="content">
      <ul class="ck-list uk-margin-small-bottom">
        <li><a class="text-grey" href="<?= $this->Url->build('/savings-calculation') ?>"><?= __("Хадгаламжийн тооцоолуур") ?></a></li>
        <li><a class="text-grey" href="<?= $this->Url->build('/loan-calculation') ?>"><?= __("Зээлийн тооцоолуур") ?></a></li>
      </ul>
    </div>
  </div>
<?php endif; ?>

<div class="block2 uk-padding-remove">
  <a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'view', 'financial-consultancy']) ?>" class="title text-grape uk-text-uppercase"><?= $this->Html->image('icons/sanhuugiin-zuvluh-grape.png', ['width' => '18']) ?> <?= __("Санхүүгийн зөвлөгөө") ?></a>
</div>

<div class="block2 uk-padding-remove">
  <a href="<?= $this->Url->build(['controller' => 'Faqs', 'action' => 'view', $hasFaq]) ?>" class="title text-grape uk-text-uppercase"><?= $this->Html->image('icons/question_mark.png', ['width' => '18']) ?> <?= __("Асуулт хариулт") ?></a>
</div>