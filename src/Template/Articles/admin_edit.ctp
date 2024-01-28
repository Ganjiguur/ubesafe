<!--TODO ask for delete if article is empty-->

<?php
    require_once(ROOT . DS . 'plugins' . DS . "tools.php");
    
    echo $this->Html->css('/cms/css/cropper', ['block'=>true]);
    echo $this->Html->css(getUikitCssPath('components/form-file.almost-flat.min'), ['block' => true]);
    
    $this->loadHelper('Form', ['templates' => 'md_form', 'type' => 'file']);
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('Нийтлэл') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small">
                    <?php if ($article->published) : ?>
                        <span class="uk-text-warning"><?= __('Autosave is disabled for published articles.') ?></span>
                    <?php endif; ?>
                    <i class="uk-icon uk-icon-spin uk-icon-refresh autosave_status" style="display:none"></i>
                    <span class="autosave_success" style="display:none"><?= __('Автоматаар хадгалагдлаа') ?></span>
                    <span class="autosave_error" style="display:none"><?= __('Автоматаар хадгалахад алдаа гарлаа') ?></span>
                </span>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action'=>'index'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
        
    </div>
    <div id="page_content_inner">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create($article, ['type' => 'file']) ?>
        <div class="md-fab-wrapper">
            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success', 'id'=>'saveButton', 'escape' => false]) ?>  
        </div>
        <?= $this->element('articles/article_form') ?>
    <?= $this->Form->end(); ?>
    </div>
</div>

<?= $this->element("cms/editor") ?>
<?= $this->element("articles/article_form_js") ?>
<?= $this->Html->script('/cms/js/cropper.min', ['block' => true]); ?>
