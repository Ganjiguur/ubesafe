<?php


?>
<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Form preview') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('This is just for preview. The form will have different design according to the site template.') ?></span>
            </div>
            <div class="uk-float-right">
<?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), $this->request->referer(), ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
<?= $this->Flash->render() ?>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-2-3 uk-width-large-3-4">
                <div class="md-card">
                    <?php $formRecord = isset($formRecord) ? $formRecord : null ?>
<?= $this->Form->create($formRecord, ['url' => ['controller' => 'FormRecords', 'action' => 'submit'], 'templates' => 'md_form']) ?>
                    <div class="md-card-content">
                        <h3 class="heading_a">
                            <?= $form->title ?>
                        </h3>     
                        <?php if (!empty($form->description)): ?>
                        <blockquote class="uk-margin-top uk-margin-medium-bottom">
                            <?= $form->description ?>
                        </blockquote>
                        <?php endif; ?>
                        <div class="uk-grid uk-margin-top" data-uk-grid-margin>
                            <?php foreach ($form->fields as $field): ?>

                                <?php
                                $column = '1-1';
                                if (isset($field['column']) && isset($form->columns[$field['column']])) {
                                    $column = $field['column'];
                                }
                                ?>
                                <div class="uk-width-medium-<?= $column ?>">
                                    <label><?= $field['label'] ?></label>
                                    <?php
                                    $options = [
                                        'class' => 'md-input label-fixed',
                                        'label' => false,
                                        'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : '',
                                        'required' => empty($field['required']) ? false : true
                                    ];
                                    switch ($field['type']) {
                                        case 'text':
                                            $options['rows'] = 2;
                                            break;
                                        case 'string':
                                            $options['type'] = 'text';
                                            break;
                                        default:
                                            $options['type'] = $field['type'];
                                            break;
                                    }
                                    $name = Inflector::slug(mb_strtolower($field['label']), '_');
                                    echo $this->Form->input('data[' . $name . ']', $options);
                                    ?>
                                </div>
                            <?php endforeach; ?>
                            <div class="uk-width-1-1">
                                <?= $this->Form->hidden('form_id', ['value' => $form->id]) ?>
                                <?=
                                $this->Form->hidden('source', [
                                    'value' => isset($title_for_page) ? $title_for_page : 'N/A'
                                ])
                                ?>
<?= $this->Form->button($form->submit_text, ['class' => 'md-btn md-btn-wave waves-effect waves-button']) ?>
                            </div>
                        </div>
                    </div>
<?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>