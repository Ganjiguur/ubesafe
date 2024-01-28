<div class="uk-form-row md-input-wrapper label-fixed md-input-filled rw-pages">
  <label class=""><?= __('Show on Pages') ?> (<span id="page_stat"></span>)</label>
    <?= $this->Form->hidden('pages[_ids]') ?>
    <div class="uk-scrollable-box uk-margin-top">
        <?php
        $_pages = [];
        foreach ($model->pages ? : [] as $page) {
            $_pages[$page->id] = true;
        }
        ?>
        <?php foreach ($pages as $id => $dname) : ?>
            <div class="checkbox uk-margin-small">
                <?= $this->Form->checkbox('pages[_ids][]', ['value' => $id, 'hiddenField' => false, 'data-md-icheck' => '', 'id' => 'pages-ids-' . $id, 'checked' => isset($_pages[$id])]); ?>
                <?= $this->AppView->getMultSelectLabel('pages-ids-' . $id, $dname) ?>
            </div>
        <?php endforeach; ?>
    </div>
  
  <?php if (false) : ?>
    <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    
    function showPagesState() {
      var total = 0;
      $(".rw-pages input[type=checkbox]").each(function() {
        if($(this).is(":checked")) {
          total++;
        }
      });
      $("#page_stat").html(total);
    }

    $(".rw-pages input[type=checkbox]").on('ifChanged', function(event){
      showPagesState();
    });

    showPagesState();

  
<?php if (false) : ?>
    </script>
  <?php else: ?>
    <?php $this->Html->scriptEnd(); ?>
  <?php endif; ?>
</div>