<%

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
        ->filter(function($field) use ($schema) {
    return $schema->columnType($field) !== 'binary';
});

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>

<?php
$this->loadHelper('Form', ['templates' => 'md_form']);
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('<%= Inflector::humanize($action) %> <%= $singularHumanName %>') ?></h1>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action' => 'index'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>      
        <?= $this->Form->create($<%= $singularVar %>) ?>
        <div class="md-fab-wrapper">
            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success', 'id' => 'saveButton', 'escape' => false]) ?>  
        </div>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-1-1 uk-width-large-1-1">
                <div class="md-card">
                    <div class="md-card-content">
                        <%
                        foreach ($fields as $field) {
                            if (in_array($field, $primaryKey)) {
                                continue;
                            }
                            if (isset($keyFields[$field])) {
                                %>
                        <div class="uk-form-row">
                                    <label><?= __('<%= Inflector::humanize($field) %>') ?></label>
                                    <%
                                    $fieldData = $schema->column($field);
                                    if (!empty($fieldData['null'])) {
                                        %>
                                        <?= $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true,'class' => 'md-input  label-fixed', 'label' => false]); ?>
                                        <%
                                    } else {
                                        %>
                                        <?= $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>,'class' => 'md-input  label-fixed', 'label' => false]);?>
                                        <%
                                    }
                                    %>
                        </div>   
                                <%
                                continue;
                            }
                            if (!in_array($field, ['created', 'modified', 'updated'])) {
                                %>
                        <div class="uk-form-row">
                            <label><?= __('<%= Inflector::humanize($field) %>') ?></label>
                                    <%
                                    $fieldData = $schema->column($field);
                                    if (in_array($fieldData['type'], ['date', 'datetime', 'time']) && (!empty($fieldData['null']))) {
                                        %>
                                        <?= $this->Form->input('<%= $field %>', ['empty' => true, 'class' => 'md-input', 'label' => false]); ?>
                                        <%
                                    } elseif ($fieldData['type'] == 'boolean' || ($fieldData['type'] == 'integer' && $fieldData['length'] == 1)) {
                                        %>
                                        <?= $this->Form->checkbox('<%= $field %>', ['class' => 'md-input', 'label' => false, 'data-switchery' => '']); ?>
                                        <%
                                    } else {
                                        %>
                                        <?= $this->Form->input('<%= $field %>', ['class' => 'md-input', 'label' => false]); ?>
                                        <%
                                    }
                                    %>
                        </div>
                                <%
                            }
                        }
                        if (!empty($associations['BelongsToMany'])) {
                            foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
                                %>       
                        <div class="uk-form-row">
                                    <label"><?= __('<%= Inflector::humanize($assocData['property']) %>') ?></label>
                                    <?= $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>,'class' => 'md-input label-fixed', 'label' => false]) ?>
                                </div>
                                <%
                            }
                        }
                        %>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>