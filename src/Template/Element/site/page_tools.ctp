<?php
if (!empty($jobs)) :
echo $this->Html->script("vue.min", ['block' => true]);

$_style = isset($_style) ? $_style : '';
?>

  <div class="uk-grid uk-grid-medium tool-box has-border" data-uk-grid-match="{target:'.block1'}" data-uk-grid-margin id="page-tools" style="<?= $_style ?>">
    <div class="uk-width-medium-1-2 uk-width-1-1">
      <div class="block1 uk-clearfix" v-cloak="v-cloak">
        <label class="text-bold uk-display-block" style="margin-bottom:17px;">
          <?= $this->Html->image('icons/jobs.png', ['width' => '30'])?>
          <span class="uk-hidden-small"><?= __("Зарлагдсан нээлттэй ажлын байр") ?></span>
          <span class="uk-visible-small"><?= __("Нээлттэй ажлын байр") ?></span> (<?= count($jobs) ?>)
        </label>
        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
          <button class="uk-button uk-text-truncate"><i class="uk-icon-angle-down"></i>{{ jobs[selectedJob]['name'] }}</button>
          <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
            <ul class="uk-nav uk-nav-dropdown">
              <li v-for="(job, $index) in jobs"><a @click="selectedJob = $index" class="uk-dropdown-close">{{job['name']}}</a></li>
            </ul>
          </div>
        </div>
        <a v-bind:href="jobs[selectedJob]['link']" target="_blank" class="grapebutton dropdown-right float-right-small"><?= __("Дэлгэрэнгүй") ?></a>
      </div>
    </div>
    <div class="uk-width-medium-1-4 uk-width-small-1-2 uk-width-1-1">
      <div class="block1 uk-clearfix uk-position-relative" v-cloak="v-cloak">
        <label class="text-bold uk-display-block uk-margin-bottom">
          <i class="uk-float-right text-grape uk-icon-angle-right transition text-20 uk-text-bold"></i>
          <?= $this->Html->image('icons/tender.png', ['width' => '25', 'style' => 'margin-top:-2px'])?>
          <?= __("Худалдан авалт") ?>
        </label>
        <p class="text-13l uk-margin-bottom-remove"><?= __("Сонгон шалгаруулалтын үйл ажиллагаа болон зарууд") ?></p>
        <a class="uk-position-cover" href="<?= $this->Url->build(['controller'=>'pages', 'action'=>'view', 'procurement']); ?>"></a>
      </div>
    </div>  
    <div class="uk-width-medium-1-4 uk-width-small-1-2 uk-width-1-1">
      <div class="block1 uk-clearfix uk-position-relative" v-cloak="v-cloak">
        <label class="text-bold uk-display-block uk-margin-bottom">
          <i class="uk-float-right text-grape uk-icon-angle-right transition text-20 uk-text-bold"></i>
          <?= $this->Html->image('icons/shield.png', ['width' => '20', 'style' => 'margin-top:-2px'])?>
          <?= __("Аюулгүй байдал") ?>
        </label>
        <p class="text-13l uk-margin-bottom-remove"><?= __("Аюулгүй байдалтай холбоотой
зөвлөгөө, мэдээлэл") ?></p>
        <a class="uk-position-cover" href="<?= $this->Url->build(['controller'=>'pages', 'action'=>'view', 'security']); ?>"></a>
      </div>
    </div>  
  </div>


  <?php if (false) : ?>
    <script>
  <?php else: ?>
    <?php $this->Html->scriptStart(['block' => true]); ?>
  <?php endif; ?>
      var vm = new Vue({
      el: '#page-tools',
      data: {
        selectedJob: 0,
        jobs: <?= json_encode($jobs) ?>,
      }
    });
  <?php if (false) : ?>
    </script>
  <?php else: ?>
    <?php $this->Html->scriptEnd(); ?>
  <?php endif; ?>
  
<?php endif; ?>