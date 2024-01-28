<?php
echo $this->Html->css('/cms/css/components/nestable.almost-flat.css', ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);
$slugify = $mode == "edit" ? [] : ['data-slugify' => 'slug'];

function printTree($data, $level, $self) {
  foreach ($data as $item) :
    ?>
    <li class="uk-nestable-item" rw-data-id="<?= $item->id ?>">
      <div class="uk-nestable-panel">
        <div class="uk-nestable-toggle" data-nestable-action="toggle"></div>
        <?= $self->AppView->getStatusIndicator($item->active); ?>
        <?= $self->Html->link($item->name, $item->route, ['title' => $self->Url->build($item->route, true), 'data-uk-tooltip' => "{cls:'long-text uk-tooltip-small'}", 'style' => 'line-height:32px', 'target' => '_blank']) ?>
<?php if (!empty($item->description)): ?>
          <span style="min-width: 80px;margin-left: 20px;display:inline-block">
            <span class="uk-badge" data-uk-tooltip="{cls:'long-text'}" title="<?= h($item->description) ?>"><?= __("Тайлбар") ?></span>
            </span>
          <?php endif; ?>
        
        <div class="uk-float-right">
          
          <span style="min-width: 180px;display:inline-block">Нийтлэлийн тоо: <strong><?= count($item->articles) ?></strong></span>
            
          
<span style="min-width: 230px;display:inline-block">Сүүлд зассан: <strong><?= getDateString($item->modified) ?></strong></span>
          
          <div class="uk-button-group">
    <?= $self->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $item->id], ['class' => 'uk-display-inline-block delete-btn', 'confirm' => __('Та {0} категорийг устгах гэж байна уу?', h($item->name)), 'escape' => false, 'data-uk-tooltip' => '', 'title' => __("Устгах"), 'style' => count($item->children) > 0 ? 'visibility: hidden' : '']) ?>


    <?= $self->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'index', $item->id], ['class' => 'uk-display-inline-block ', 'title' => __("Засах"), 'escape' => false, 'data-uk-tooltip' => '']) ?>
          </div>
        </div>
        <div class="uk-clearfix"></div>
      </div>
      
        <?php if (count($item->children) > 0) : ?>
        <ul>
        <?php printTree($item->children, $level + 1, $self); ?>
        </ul>
    <?php endif; ?>
    </li>
  <?php
  endforeach;
}
?>

<div id="page_content">
  <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
    <div class="uk-clearfix" data-uk-margin>
      <div class="uk-float-left">
        <h1><?= __('Категориуд') ?></h1>
        <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Нийтлэлийн категорууд') ?></span>
      </div>
      <div class="uk-float-right">
        <div id="btn_save_order" class="uk-display-inline-block" style="margin-left: 10px;padding-left: 10px;">
            <button class="md-btn md-btn-success md-btn-small md-btn-wave" onclick="saveOrder()" style="display: none">Save menu order <i class="uk-icon-spin uk-icon-circle-o-notch" style="display: none"></i></button>
            <span style="display: none;line-height: 31px;" class="uk-text-success">Амжилттай!</span>
        </div>
      </div>
    </div>
  </div>
  <div id="page_content_inner">
<?= $this->Flash->render() ?>

    <div class="md-fab-wrapper">
      <button class="md-fab md-fab-success" onclick="showModal('#category-modal', true)"><i class="material-icons">add</i></button>
    </div>
    <div class="uk-grid" data-uk-grid-margin>
      <div class="uk-width-1-1">
        <ul id="nestable_items" class="uk-nestable" data-uk-nestable>
          <?php printTree($categories, 0, $this); ?>
        </ul>
      </div>
      <?php if (empty($categories)): ?>
        <div class="uk-width-1-1"><?= __('Мэдээлэл байхгүй байна') ?></div>
      <?php endif; ?> 
    </div>
  </div>
</div>
</div>
</div>

<div class="uk-modal" id="category-modal">
  <div class="uk-modal-dialog">
<?php echo $this->Form->create($category); ?>
    <div class="uk-modal-header">
      <div class="uk-clearfix">
        <div class="uk-float-left">
          <h3 class="uk-modal-title"><?= $title_for_box ?></h3>
        </div>
        <div class="uk-float-right">
<?= $this->Form->checkbox('active', ['data-switchery' => '', 'id' => 'active_check']) ?>
          <label for="active_check" class="inline-label"><?= __('Идэвхтэй') ?></label>
        </div>
      </div>
    </div>
    <div class="uk-grid" data-uk-grid-margin>
      <div class="uk-width-small-1-2">
        <div class="uk-margin-bottom">
          <label><?= __('Нэр') ?></label>
<?= $this->Form->input('name', ['class' => 'md-input', 'label' => false] + $slugify); ?>
          <span class="uk-form-help-block"><?= __("The name is how it appears on your site.") ?></span>
        </div>
        <div class="">
          <label><?= 'Slug  (' . __('Линк') . ')' ?></label>
            <?= $this->Form->input('slug', ['label' => false, 'class' => 'md-input', 'data-show-change' => 'slug_val']); ?>
          <span class="uk-form-help-block">
            <?= $this->Url->build(['controller' => 'articles', 'action' => 'category'], true) ?>/<strong id="slug_val"><?= $category->slug ?></strong>
            <br>
            <br>
<?= __("The 'slug' is the URL-friendly version of the name. It must be all lowercase and contains only letters, numbers, and hyphens(-).") ?>
          </span>
        </div>
      </div>
      <div class="uk-width-small-1-2">
        <div class="uk-margin-bottom">
          <label><?= __('Нэр') . ' ' . __("(Англи)") ?></label>
<?= $this->Form->input('name_en', ['label' => false, 'class' => 'md-input']); ?>
          <span class="uk-form-help-block"><?= __("The name is how it appears on your English site.") ?></span>
        </div>
        <div class="uk-margin-bottom">
          <label><?= __('Эцэг') ?></label>
<?php echo $this->Form->input('parent_id', ['class' => 'md-input label-fixed', 'label' => false, 'options' => $parents, 'empty' => __('Эцэггүй')]); ?>
          <span class="uk-form-help-block"><?= __("You might have a Music category, and under that have children categories for Rock and HipHop.") ?></span>
        </div>
      </div>
      <div class="uk-width-small-1-1">
        <label><?= __("Тайлбар") ?>  </label>
<?= $this->Form->input('description', ['label' => false, 'class' => 'md-input']) ?>
      </div>
    </div>
    <div class="uk-modal-footer uk-text-right">
      <?php
      if ($mode == 'add') {
        echo $this->Form->button(__("Шинээр нэмэх"), ['class' => 'md-btn md-btn-flat md-btn-flat-primary']);
      } else {
        echo $this->Html->link(__("Буцах"), ['action' => 'index'], ['class' => 'md-btn md-btn-flat']);
        echo $this->Form->button(__("Хадгалах"), ['class' => 'md-btn md-btn-flat md-btn-flat-primary']);
      }
      ?>
    </div>
<?php echo $this->Form->end(); ?>
  </div>
</div>   

<?= $this->element('cms/nestable_js') ?>

<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    $(function () {
    var bgClose = <?= $mode == "edit" ? 'false' : 'true' ?>;
    if (<?= ($category->errors() || $mode == "edit") ? 1 : 0 ?>) {
      showModal('#category-modal', bgClose);
    }
  });
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>