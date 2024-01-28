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
<h3><?php echo $title_for_box; ?></h3>
<div class="uk-form">
    <?= $this->Form->create($<%= $singularVar %>, ['class'=>'uk-form']) ?>

    <%
    foreach ($fields as $field) {
        if (in_array($field, $primaryKey)) {
            continue;
        }
        if (isset($keyFields[$field])) {
            %>
    <div class="uk-form-row uk-width-1-1">
                <label class="uk-form-label"><?= __('<%= Inflector::humanize($field) %>') ?></label>    
                <%
                $fieldData = $schema->column($field);
                if (!empty($fieldData['null'])) {
                    %>
                    <?= echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true,'class' => 'uk-width-1-1', 'label' => false]); ?>
                    <%
                } else {
                    %>
                    <?= $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>,'class' => 'uk-width-1-1', 'label' => false]);?>
                    <%
                }
                %>
    </div>   
            <%
            continue;
        }
        if (!in_array($field, ['created', 'modified', 'updated'])) {
            %>
    <div class="uk-form-row uk-width-1-1">
                <label class="uk-form-label"><?= __('<%= Inflector::humanize($field) %>') ?></label>    
                <%
                $fieldData = $schema->column($field);
                if (in_array($fieldData['type'], ['date', 'datetime', 'time']) && (!empty($fieldData['null']))) {
                    %>
                    <?= echo $this->Form->input('<%= $field %>', ['empty' => true, 'class' => 'uk-width-1-1', 'label' => false]); ?>
                    <%
                } else {
                    %>
                    <?= $this->Form->input('<%= $field %>', ['class' => 'uk-width-1-1', 'label' => false]); ?>
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
    <div class="uk-form-row uk-width-1-1">
                <label class="uk-form-label"><?= __('<%= Inflector::humanize($assocData['property']) %>') ?></label>    
                <?= $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>,'class' => 'uk-width-1-1', 'label' => false]);
                ?>
            </div>  
            <%
        }
    }
    %>
    <hr class="uk-article-divider">
    <?php
    if ($mode == 'add') {
        echo $this->Form->button(__('Add New'), array('class' => 'uk-button uk-button-primary'));
    } else {
        echo $this->Html->link(__('Back'), array('action' => 'index'), array('class' => 'uk-button'));
        echo $this->Form->button(__('Save'), array('class' => 'uk-button  uk-button-primary uk-float-right'));
    }
    echo $this->Form->end();
    ?>
</div>
