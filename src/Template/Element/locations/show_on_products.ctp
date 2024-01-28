<div class="uk-form-row md-input-wrapper label-fixed md-input-filled rw-products">
    <label class=""><?= __('Show on Products') ?> (<span id="product_stat"></span>)</label>
    <?= $this->Form->hidden('products[_ids]') ?>
    <div class="uk-scrollable-box uk-margin-top">
        <?php
        $_products = [];
        foreach ($model->products ? : [] as $product) {
            $_products[$product->id] = true;
        }
        ?>
        <?php foreach ($products as $id => $dname) : ?>
            <div class="checkbox uk-margin-small">
                <?= $this->Form->checkbox('products[_ids][]', ['value' => $id, 'hiddenField' => false, 'data-md-icheck' => '', 'id' => 'products-ids-' . $id, 'checked' => isset($_products[$id])]); ?>
                <?= $this->AppView->getMultSelectLabel('products-ids-' . $id, $dname) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (false) : ?>
      <script>
    <?php else: ?>
      <?php $this->Html->scriptStart(['block' => true]); ?>
    <?php endif; ?>
      function showProductState() {
        var total = 0;
        $(".rw-products input[type=checkbox]").each(function() {
          if($(this).is(":checked")) {
            total++;
          }
        });
        $("#product_stat").html(total);
      }

      $(".rw-products input[type=checkbox]").on('ifChanged', function(event){
        showProductState();
      });

      showProductState();

    <?php if (false) : ?>
      </script>
    <?php else: ?>
      <?php $this->Html->scriptEnd(); ?>
    <?php endif; ?>
</div>