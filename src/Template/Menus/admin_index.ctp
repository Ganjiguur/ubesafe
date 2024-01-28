<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css('/cms/css/components/nestable.almost-flat.css', ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);
$defaultFilterClass = 'md-btn md-btn-small md-btn-wave ';
?>

<?php
global $menulang;
$menulang = $menu_lang;

function printTree($data, $level, $self) {
  global $menulang;
  foreach ($data as $menuitem) :?>
    <li class="uk-nestable-item" rw-data-id="<?= $menuitem->id ?>">
      <div class="uk-nestable-panel">
        <div class="uk-nestable-toggle" data-nestable-action="toggle"></div>
        <?= $self->AppView->getStatusIndicator($menuitem->active); ?>
          <?php if($menuitem->route !== '#'): ?>
            <?= $self->Html->link($menuitem->name, $menuitem->route, ['title' => $self->Url->build($menuitem->route, true), 'data-uk-tooltip' => "{cls:'long-text uk-tooltip-small'}", 'style' => 'line-height:32px', 'target' => '_blank', 'escape' => false]) ?>
          <?php else: ?>
          <span title="Холбоосгүй цэс" data-uk-tooltip style="line-height:32px"><?= $menuitem->name ?></span>
          <?php endif; ?>
          <span class="hover-to-see">
          <?php if($menuitem->show_on_mainmenu || $menuitem->show_on_business): ?>
            <i title="Үндсэн цэсэнд харагдна" data-uk-tooltip style="line-height:32px" class="uk-icon-desktop"></i>
          <?php endif; ?>
            
          <?php if($menuitem->show_on_mobile): ?>
          <i title="Мобайл цэсэнд харагдна" data-uk-tooltip style="line-height:32px" class="uk-icon-mobile"></i>
          <?php endif; ?>
          <?php if($menuitem->show_on_footer): ?>
          <i title="Сайтын хөл хэсэгт харагдна" data-uk-tooltip style="line-height:32px" class="uk-icon-download"></i>
          <?php endif; ?>
          </span>
        <div class="uk-float-right">
          <div class="uk-button-group">
            <?= $self->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'index', $menuitem->id, 'menu_lang' => $menulang], ['class' => 'uk-display-inline-block ', 'title' => __("Засах"), 'escape' => false, 'data-uk-tooltip' => '']) ?>
            
            <?= $self->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $menuitem->id], ['class' => 'uk-display-inline-block delete-btn', 'confirm' => __('Та {0} цэсийг устгах гэж байна уу?', h($menuitem->name)), 'escape' => false, 'data-uk-tooltip' => '', 'style' => count($menuitem->children) > 0 ? 'visibility: hidden' : '', 'title' => __("Устгах")]) ?>
          </div>
        </div>
      </div>

      <?php if (count($menuitem->children) > 0) : ?>
        <ul>
          <?php printTree($menuitem->children, $level + 1, $self); ?>
        </ul>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
<?php } ?>

<div id="page_content">
  <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
    <div class="uk-clearfix" data-uk-margin>
      <div class="uk-float-left">
        <h1><?= __('Сайтын цэс') ?></h1>
        <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Сайтын монгол, англи цэсүүд') ?></span>
      </div>
      <div class="uk-float-right">    
        <div class="md-btn-group uk-display-inline-block">
          <?= $this->Html->link(__("Монгол"), ['action' => 'index', 'menu_lang' => 'mn'], ['class' => $defaultFilterClass . (($menu_lang == 'mn') ? 'md-btn-primary' : '')]) ?>
          <?= $this->Html->link(__("Англи"), ['action' => 'index', 'menu_lang' => 'en'], ['class' => $defaultFilterClass . (($menu_lang == 'en') ? 'md-btn-primary' : '')]) ?>
        </div>
        <div id="btn_save_order" class="uk-display-inline-block" style="margin-left: 10px;padding-left: 10px;border-left: 1px solid #eee;">
          <button class="md-btn md-btn-success md-btn-small md-btn-wave" onclick="saveOrder()" style="display: none">Save menu order <i class="uk-icon-spin uk-icon-circle-o-notch" style="display: none"></i></button>
          <span style="display: none;line-height: 31px;" class="uk-text-success">Амжилттай!</span>
        </div>
      </div>
    </div>
  </div>
  <div id="page_content_inner">
    <?= $this->Flash->render() ?>
    <div class="md-fab-wrapper">
      <button class="md-fab md-fab-success" onclick="showModal('#menu-modal', true)"><i class="material-icons">add</i></button>
    </div>

    <div class="uk-grid" data-uk-grid-margin>
      <div class="uk-width-1-1">
        <ul id="nestable_items" class="uk-nestable" data-uk-nestable>
          <?php printTree($menus, 0, $this); ?>
        </ul>
      </div>
      <?php if(empty($menus)):?>
        <div class="uk-width-1-1"><?=__('Мэдээлэл байхгүй байна')?></div>
      <?php endif;?> 
    </div>
  </div>
</div>

<div class="uk-modal" id="menu-modal">
  <div class="uk-modal-dialog">
    <?php echo $this->Form->create($menu); ?>
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

    <?= $this->Form->hidden('language', ['value' => $menu_lang]); ?>
    <div class="uk-grid" data-uk-grid-margin>
      <div class="uk-width-1-1">
        <label><?= __('Нэр') ?></label>
        <?= $this->Form->input('name', array('label' => false, 'class' => 'md-input')); ?>
      </div>
      <div class="uk-width-small-1-1">
                <label><?= __('Эцэг') ?></label>
                <?php echo $this->Form->input('parent_id', ['class' => 'md-input label-fixed', 'label' => false, 'options' => $parentMenus, 'empty'=>__('Эцэггүй')]); ?>
            </div>
      <?php /* 
      <div class="uk-width-small-1-2">
        <label><?= __('Нэмэлт хэлбэржүүлэлт') ?></label>    
        <?= $this->Form->input('attr', ['class' => 'md-input label-fixed', 'label' => false, 'placeholder' => 'ex: target="_blank"']); ?>
      </div>
       * */?>
      <div class="uk-width-small-1-2">
        <label><?= __('Холбоос') ?></label>
        <?= $this->Form->input('link_type', ['class' => 'md-input', 'label' => false, 'options' => $menu->link_types]); ?>
      </div>
      <div class="uk-width-small-1-2 link-value">
        <label><?= __('Веб хуудас') ?></label>
        <?= $this->Form->unlockField('page'); ?>
        <?= $this->Form->input('page', ['class' => 'md-input', 'label' => false, 'value' => $menu->link]); ?>
      </div>
      <div class="uk-width-small-1-2 link-value">
        <label><?= __('Веб линк') ?></label>
        <?= $this->Form->input('url', ['class' => 'md-input label-fixed', 'label' => false, 'placeholder' => 'ex:www.google.com', 'value' => $menu->link]); ?>
      </div>
      <div class="uk-width-small-1-2 link-value">
        <label><?= __('Категори') ?></label>
        <?= $this->Form->unlockField('category'); ?>
        <?= $this->Form->input('category', ['class' => 'md-input', 'label' => false, 'value' => $menu->link]); ?>
      </div>
      <div class="uk-width-small-1-2 link-value">
        <label><?= __('Бүтээгдэхүүн') ?></label>
        <?= $this->Form->unlockField('product'); ?>
        <?= $this->Form->input('product', ['class' => 'md-input', 'label' => false, 'value' => $menu->link]); ?>
      </div>
      <div class="uk-width-small-1-1 uk-form-row">
        <?= $this->Form->checkbox('show_on_mainmenu', ['data-switchery' => '', 'id' => 'show_on_mainmenu']) ?>
        <label for="show_on_mainmenu" class="inline-label"><?= __('Үндсэн цэсэнд харагдна') ?> (<?= __("Иргэн") ?>)</label>
      </div>
      <div class="uk-width-small-1-1 uk-form-row">
        <?= $this->Form->checkbox('show_on_business', ['data-switchery' => '', 'id' => 'show_on_business']) ?>
        <label for="show_on_business" class="inline-label"><?= __('Үндсэн цэсэнд харагдна') ?> (<?= __("Байгууллага") ?>)</label>
      </div>
      <div class="uk-width-small-1-1 uk-form-row">
        <?= $this->Form->checkbox('show_on_footer', ['data-switchery' => '', 'id' => 'show_on_footer']) ?>
        <label for="show_on_footer" class="inline-label"><?= __('Сайтын хөл хэсэгт харагдна') ?></label>
      </div>
      <div class="uk-width-small-1-1 uk-form-row">
        <?= $this->Form->checkbox('show_on_mobile', ['data-switchery' => '', 'id' => 'show_on_mobile']) ?>
        <label for="show_on_mobile" class="inline-label"><?= __('Мобайл цэсэнд харагдна') ?></label>
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
    if (<?= ($menu->errors() || $mode == "edit") ? 1 : 0 ?>) {
      showModal('#menu-modal', bgClose);
    }

    if (<?= empty($this->request->params['pass']) ? 0 : 1 ?>) {
      showModal('#menu-modal');
    }
      
    $('.link-value').hide();
    
    var currentLink = "<?= $menu->link_type ?>";
    $('#' + currentLink).closest('.link-value').show();
    $('#link-type').change(function () {
      $('.link-value').hide();
      $('#' + this.value).closest('.link-value').show().addClass('uk-grid-margin');
    });
  });
  
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>