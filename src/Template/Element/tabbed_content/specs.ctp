<?php  $this->Form->unlockField('specs' . $_lang) ?>
<style>
    #specs_content_en > li, #specs_content > li {
        padding: 0px 5px 5px 50px;
    }
</style>
<div class="uk-margin-bottom">
    <div class="uk-button-group" id="spec_actions<?= $_lang ?>">
        <button type="button" onclick="addSpec('<?= $_lang ?>')" class="md-btn md-btn-icon" title="Add a new spec" data-uk-tooltip><i class="uk-icon-plus"></i></button>
        <button type="button" onclick="removeSpec('<?= $_lang ?>', -1)" class="md-btn md-btn-icon" title="Remove last spec" data-uk-tooltip><i class="uk-icon-minus"></i></button>
    </div>
    <button type="button" onclick="destroySpecs('<?= $_lang ?>')" id="specs_destroy<?= $_lang ?>" class="md-btn md-btn-icon md-btn-danger uk-float-right"><i class="uk-icon-remove"></i> <?= __("Remove all specs") ?></button>
    <button type="button" onclick="initSpecs('<?= $_lang ?>')" id="specs_init<?= $_lang ?>" class="md-btn md-btn-icon md-btn-success uk-float-right"><i class="uk-icon-plus"></i> <?= __("Add spec content") ?></button>
</div>
<div class="uk-width-1-1" id="specs_container<?= $_lang ?>">
    <?php if (!empty($_entity['specs' . $_lang])): ?>
        <ul class="uk-tab" data-uk-tab="{connect:'#specs_content<?= $_lang ?>', swiping:false}" id="specs_header<?= $_lang ?>" rw-spec-count="<?= count($_entity['specs' . $_lang]) ?>">
          <?php foreach ($_entity['specs' . $_lang] as $n => $item): ?>
            <?php if (!is_array($item)) {
                continue;
            } ?>
            <?php $item = $item + ['name' => 'empty', 'content' => '', 'type' => '1', 'title' => ''] ?>
          
            <li class="spec_head_<?= $n ?>">
                <a href="#">
                    <span id="specsn<?= $_lang ?><?= $n ?>"><?= $item['name'] ?></span>
                    <button onclick="editSpec('<?= $_lang ?>', <?= $n ?>)" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button>
                </a> 
            </li>
          <?php endforeach; ?>
        </ul>
        <ul id="specs_content<?= $_lang ?>" class="uk-switcher uk-margin">
          <?php foreach ($_entity['specs' . $_lang] as $n => $item): ?>
              <?php if (!is_array($item)) {
                  continue;
              } ?>
          <?php $item = $item + ['name' => 'empty', 'content' => '', 'type' => '1', 'title' => ''] ?>
            <li class="spec_body_<?= $n ?>">
              <div class="uk-grid uk-margin-bottom uk-form">
                <div class="uk-width-1-2">
                  <?= $this->Form->input('specs' . $_lang . '[' . $n . '][title]', ['label' => false, 'value' => $item['title'], 'class' => "uk-width-1-1", 'placeholder' => 'title']) ?>
                </div>
                <div class="uk-width-1-2">
                  <?= $this->Form->input('specs' . $_lang . '[' . $n . '][type]', ['label' => false, 'class' => 'uk-width-1-1', 'style' => 'max-width:300px', 'value' => $item['type'], 'options' => ['1' => 'Show only on comparison', '2' => 'Show only on brief introduction', '3' => 'Show on both']]) ?>
                </div>
              </div>
              
              <?= $this->Form->hidden('specs' . $_lang . '[' . $n . '][name]', ['value' => $item['name'], 'id' => "specs" . $_lang . "" . $n]) ?>
              <?= $this->Form->textarea('specs' . $_lang . '[' . $n . '][content]', ['class' => 'specs_content' . $_lang . '_' . $n, 'placeholder' => '"' . $item['name'] . '" spec content', 'label' => false, 'required' => false, 'rows' => 2, 'value' => $item['content']]); ?>
              <div class="uk-text-right">
                <button type="button" onclick="removeSpec('<?= $_lang ?>', <?= $n ?>)" class="md-btn md-btn-icon uk-margin-top"><i class="uk-icon-remove"></i> <?= __("Remove this spec") ?></button>
              </div>
            </li>
        <?php endforeach; ?>
        </ul>
<?php endif; ?>
</div>