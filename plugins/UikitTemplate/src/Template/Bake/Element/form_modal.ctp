<%

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

<div class="uk-modal" id="form-modal">
    <div class="uk-modal-dialog">
        <?= $this->Form->create($<%= $singularVar %>) ?>
        <div class="uk-modal-header">
            <h3 class="uk-modal-title"><?= $title_for_box ?></h3>
        </div>

        <div class="uk-grid" data-uk-grid-margin>

            <%
            foreach ($fields as $field) {
                if (in_array($field, $primaryKey)) {
                    continue;
                }
                if (isset($keyFields[$field])) {
                    %>
            <div class="uk-width-1-1">
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
            <div class="uk-width-1-1">
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
            <div class="uk-width-1-1">
                        <label><?= __('<%= Inflector::humanize($assocData['property']) %>') ?></label>    
                        <?= $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>,'class' => 'md-input label-fixed', 'label' => false]); ?>
                    </div>  
                    <%
                }
            }
            %>
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
            showModal('#form-modal');
    })
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>