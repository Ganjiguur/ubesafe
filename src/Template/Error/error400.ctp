<?php

use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'site';

if (Configure::read('debug')):
  $this->layout = 'dev_error';

  $this->assign('title', $message);
  $this->assign('templateName', 'error400.ctp');

  $this->start('file');
  ?>
  <?php if (!empty($error->queryString)) : ?>
    <p class="notice">
      <strong>SQL Query: </strong>
      <?= h($error->queryString) ?>
    </p>
  <?php endif; ?>
  <?php if (!empty($error->params)) : ?>
    <strong>SQL Query Params: </strong>
    <?php Debugger::dump($error->params) ?>
  <?php endif; ?>
  <?= $this->element('auto_table_warning') ?>
  <?php
  if (extension_loaded('xdebug')):
    xdebug_print_function_stack();
  endif;

  $this->end();
endif;
?>

<div class="maincontent paddingbottom-remove" style="padding:100px 0px">
  <div class="uk-container uk-container-center">
    <div class="maincontent_detail margin-top-30">  
      <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width xac-sidebar">
          
        </div>
        <div class="uk-width xac-page-content">
          <div class="maintext fr-view">
            <h1 class="title">
              <?= __('Алдаатай хүсэлт') ?>, 400!
            </h1>
            <p class="heading_b"><?= __('Уучлаарай') ?></p>
            <p class="uk-text-large">
              <?=
              sprintf(
                      __('Таны орохыг хүссэн %s хуудас олдсонгүй.'), "<span class=\"uk-text-muted\">'{$url}'</span>"
              )
              ?>
            </p>
            <a href="<?= $this->Url->build(['controller' => 'pages', 'action' => 'rewrite']) ?>"><i class="uk-icon-long-arrow-left"></i> <?= __("Эхлэл хэсэг рүү буцах") ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>