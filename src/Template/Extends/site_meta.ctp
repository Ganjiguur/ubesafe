<?= $this->Html->css('/cms/css/components/form-file.almost-flat.min', ['block' => true]); ?>
<?php
$defaultFilterClass = 'md-btn md-btn-small md-btn-wave ';
$this->loadHelper('Form', ['templates' => 'md_form']);
?>
<?php ?>
<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __("Сайтын мэдээллүүд") ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Сайтад ашиглагдах текст, зураг, файлуудын бүртгэл') ?></span>
            </div>
            <div class="uk-float-right">    
                <div class="md-btn-group uk-display-inline-block">
                    <?= $this->Html->link(__("Текст"), ['type' => 'text'], ['class' => $defaultFilterClass . (($type == 'text') ? 'md-btn-primary' : '')]) ?>
                    <?= $this->Html->link(__("Зураг"), ['type' => 'image'], ['class' => $defaultFilterClass . (($type == 'image') ? 'md-btn-primary' : '')]) ?>
                  <?= $this->Html->link(__("Файл"), ['type' => 'file'], ['class' => $defaultFilterClass . (($type == 'file') ? 'md-btn-primary' : '')]) ?>
                </div>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="md-fab-wrapper">
                    <button class="md-fab md-fab-success" onclick="showModal('#meta-modal', true)"><i class="material-icons">add</i></button>
                </div>

                <div class="uk-overflow-container">
                    <table class="uk-table ">
                        <thead>
                            <tr class="sort-header">
                                <th width="20">#</th>
                                <th width="40">key</th>
                                <th width="100"><?= __("Нэр") ?></th>
                                <th><?= __("Утга") ?></th>
                                <th width="120"><?= __("Сүүлд зассан") ?></th>
                                <th width="30"><?= __("Үйлдэл") ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 1;
                            foreach ($metas as $meta):
                                ?>
                                <tr class="<?= ($this->request->params['pass'] && $meta->id == $this->request->params['pass'][0]) ? 'uk-active' : '' ?>">
                                    <td>
                                        <?php echo $n++; ?>.</td>
                                    <td>
                                        <?= h($meta->unique_key) ?>
                                    </td>
                                    <td>
                                        <?= h($meta->label) ?>
                                    </td>
                                    <td>
                                        <?php if ($type == 'text' || $type == 'file'): ?>
                                            <?= h($meta->data) ?>
                                        <?php endif; ?>
                                        <?php if ($type == 'image' && !empty($meta->data)): ?>
                                            <?= $this->Html->image($meta->data, ['width' => '50']) ?>
                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        <?= h(date('Y/m/d G:i', strtotime($meta->modified))) ?>
                                    </td>
                                    <td>
                                        <div class='uk-button-group'>
                                            <?php echo $this->Html->link('<i class="md-icon material-icons">mode_edit</i>', ['action' => 'siteMeta', 'type' => $type, $meta->id], ['class' => 'uk-display-inline-block', 'title' => __("Засах"), 'escape' => false, 'data-uk-tooltip' => '',]); ?>
                                            <?= $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $meta->id], ['class' => 'uk-display-inline-block', 'confirm' => __('Та итгэлтэй байна уу # {0}?', $meta->unique_key), 'escape' => false, 'data-uk-tooltip' => '',]) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="uk-modal" id="meta-modal">
    <div class="uk-modal-dialog">
        <?= $this->Form->create($site_meta, ['type' => 'file']); ?>
        <div class="uk-modal-header">
            <h3 class="uk-modal-title"><?= $title_for_box ?></h3>
        </div>

        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-1-1">
                <label><?= 'Key' ?></label>
                <?= $this->Form->input('unique_key', ['class' => 'md-input', 'type' => 'text', 'label' => false]); ?>
            </div>
            <div class="uk-width-1-1">
                <label><?= __("Нэр") ?></label>
                <?= $this->Form->input('label', ['class' => 'md-input', 'type' => 'text', 'label' => false]); ?>
            </div>
            <div class="uk-width-1-1">
                <?php if ($type == 'text'): ?>
                    <label><?= __("Мэдээлэл") ?></label>
                    <?= $this->Form->input('data', ['class' => 'md-input', 'label' => false]); ?>
                <?php endif; ?>
                <?php if ($type == 'image'): ?>
                    <?= $this->element("filemanager/image_load_js", ['model' => $site_meta, 'image' => 'data']) ?>
                <?php endif; ?>
                <?php if ($type == 'file'): ?>
                  <?= $this->element("filemanager/file_load_js", ['model' => $site_meta, 'field' => 'data']) ?>
                <?php endif; ?>
            </div>
        </div>
        <?= $this->Form->hidden('type', ['value' => $type]); ?>
        <div class="uk-modal-footer uk-text-right">
            <?php
            if ($mode == 'add') {
                echo $this->Form->button(__("Шинээр нэмэх"), ['class' => 'md-btn md-btn-flat md-btn-flat-primary']);
            } else {
                echo $this->Html->link(__("Буцах"), ['action' => 'siteMeta', 'type' => $type], ['class' => 'md-btn md-btn-flat']);
                echo $this->Form->button(__("Хадгалах"), ['class' => 'md-btn md-btn-flat md-btn-flat-primary']);
            }
            ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    $(function () {
        if (<?= empty($this->request->params['pass']) ? 0 : 1 ?>)
            showModal('#meta-modal');
    })
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>