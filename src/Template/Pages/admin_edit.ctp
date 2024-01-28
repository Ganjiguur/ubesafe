<!--TODO ask for delete if page is empty-->
<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$this->loadHelper('Form', ['templates' => 'md_form']);

$slugify = $page->status ? [] : ['data-slugify'=>'slug'];
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('Вэб хуудас') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small">
                    <?php if ($page->status) : ?>
                        <span class="uk-text-warning"><?= __('Autosave is disabled for active web pages.') ?></span>
                    <?php endif; ?>
                    <i class="uk-icon uk-icon-spin uk-icon-refresh autosave_status" style="display:none"></i>
                    <span class="autosave_success" style="display:none"><?= __('Автоматаар хадгалагдлаа') ?></span>
                    <span class="autosave_error" style="display:none"><?= __('Автоматаар хадгалахад алдаа гарлаа') ?></span>
                </span>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action' => 'adminIndex'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>      
        <?= $this->Form->create($page) ?>
        <?= $this->Form->unlockField('selectize_lang'); ?>
        <div class="md-fab-wrapper">
            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success', 'id' => 'saveButton', 'escape' => false]) ?>  
        </div>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-2-3 uk-width-large-3-4">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-form-row">
                            <div class="uk-grid">  
                                <div class="uk-width-3-4">
                                    <label><?= __('Нэр') ?></label>    
                                    <?= $this->Form->input('name', ['class' => 'md-input', 'label' => false, 'required' => true]); ?>
                                </div>
                                <div class="uk-width-1-4">
                                    <?= $this->Form->input('language', ['id' => 'page_lang', 'required' => true, 'label' => false, 'options' => $page->languages]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <div class="uk-grid">  
                                <div class="uk-width-1-2">
                                    <label><?= __('Гарчиг') ?></label>    
                                    <?= $this->Form->input('title', ['class' => 'md-input', 'label' => false, 'required' => true] + $slugify); ?>
                                    <span class="uk-form-help-block"><?= __("The title is how it appears on your site.") ?></span>
                                </div>
                                <div class="uk-width-1-2">
                                    <label><?= __('Slug') ?></label>    
                                    <?= $this->Form->input('slug', ['class' => 'md-input', 'label' => false, 'required' => true, 'data-show-change' => 'slug_val']); ?>
                                    <span class="uk-form-help-block">
                                        <?= __("The 'slug' is the URL-friendly version of the title. It must be all lowercase and contains only letters, numbers, and hyphens(-).") ?>
                                        <br>
                                        <strong><?= __("Permalink") ?>: </strong>
                                        <?= $this->Url->build(['controller'=>'pages','action'=>'view'],true) ?>/<strong id="slug_val"><?= $page->slug ?></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row uk-margin-remove">
                            <label><?= __('Тайлбар') ?></label>    
                            <?= $this->Form->input('description', ['class' => 'md-input', 'label' => false, 'rows' => 1]); ?>
                        </div>
                        <div class="uk-form-row">
                            <?php echo $this->Form->input('html', ['class' => 'uk-width-1-1', 'rows' => 7, 'id' => "rewrite", 'label' => false]); ?>
                        </div>
                        <hr>
                        <div class="uk-form-row">
                            <?= $this->element('tabbed_content/content', ['_lang' => "", '_entity' => $page]) ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="uk-width-medium-1-3 uk-width-large-1-4">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-form-row">
                            <?= $this->Form->checkbox('status', ['data-switchery' => '', 'id' => 'status_check']) ?>
                            <label for="status_check" class="inline-label"><?= __("Active") ?></label>
                        </div>
                        <?= $this->element('cms/revision_count', ['entity' => $page, '_name' => 'pages']) ?>
                        <div class="uk-form-row">
                            <label><?= __('Загвар') ?></label>    
                            <?= $this->Form->input('template', ['required' => false, 'class' => 'md-input label-fixed', 'label' => false, 'options' => $page->templates]); ?>
                        </div>

                        <div class="uk-form-row">
                            <label><?= __('Категори холбох') ?></label>       
                            <?= $this->Form->input('category_ids', [ 'options' => $categories, 'label' => false, 'empty' => __("None"), 'class' => 'md-input label-fixed']); ?>
                            <span class="uk-form-help-block"><?= __("You might want to show some articles on the page.") ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
<?= $this->element("cms/editor") ?>
<?= $this->element("pages/page_form_js") ?>
<?= $this->element("tabbed_content/js") ?>