<?php  $this->Form->unlockField('tabs' . $_lang) ?>
<style>
    #tabs_content_en > li, #tabs_content > li {
        padding: 0px 5px 5px 50px;
    }
</style>
<div class="uk-margin-bottom">
    <div class="uk-button-group" id="tab_actions<?= $_lang ?>">
        <button type="button" onclick="addTab('<?= $_lang ?>')" class="md-btn md-btn-icon" title="Add a new tab" data-uk-tooltip><i class="uk-icon-plus"></i></button>
        <button type="button" onclick="removeTab('<?= $_lang ?>', -1)" class="md-btn md-btn-icon" title="Remove last tab" data-uk-tooltip><i class="uk-icon-minus"></i></button>
    </div>
    <button type="button" onclick="destroyTabs('<?= $_lang ?>')" id="tabs_destroy<?= $_lang ?>" class="md-btn md-btn-icon md-btn-danger uk-float-right"><i class="uk-icon-remove"></i> <?= __("Remove all tabs") ?></button>
    <button type="button" onclick="initTabs('<?= $_lang ?>')" id="tabs_init<?= $_lang ?>" class="md-btn md-btn-icon md-btn-success uk-float-right"><i class="uk-icon-plus"></i> <?= __("Add more content") ?></button>
</div>
<div class="uk-width-1-1" id="tabs_container<?= $_lang ?>">
    <?php if (!empty($_entity['tabs' . $_lang])): ?>
        <ul class="uk-tab" data-uk-tab="{connect:'#tabs_content<?= $_lang ?>', swiping:false}" id="tabs_header<?= $_lang ?>" rw-tab-count="<?= count($_entity['tabs' . $_lang]) ?>">
          <?php foreach ($_entity['tabs' . $_lang] as $n => $item): ?>
            <?php if (!is_array($item)) {
                continue;
            } ?>
            <?php $item = $item + ['name' => 'empty', 'content' => '', 'type', '1'] ?>
            <li class="tab_head_<?= $n ?>">
                <a href="#">
                    <span id="tabsn<?= $_lang ?><?= $n ?>"><?= $item['name'] ?></span>
                    <button onclick="editTab('<?= $_lang ?>', <?= $n ?>)" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button>
                </a> 
            </li>
          <?php endforeach; ?>
        </ul>
        <ul id="tabs_content<?= $_lang ?>" class="uk-switcher uk-margin">
          <?php foreach ($_entity['tabs' . $_lang] as $n => $item): ?>
              <?php if (!is_array($item)) {
                  continue;
              } ?>
          <?php $item = $item + ['name' => '', 'content' => ''] ?>
            <li class="tab_body_<?= $n ?>">
              <?= $this->Form->hidden('tabs' . $_lang . '[' . $n . '][name]', ['value' => $item['name'], 'id' => "tabs" . $_lang . "" . $n]) ?>
              <?= $this->Form->textarea('tabs' . $_lang . '[' . $n . '][content]', ['class' => 'tabs_content' . $_lang . '_' . $n, 'placeholder' => '"' . $item['name'] . '" tab content', 'label' => false, 'required' => false, 'rows' => 2, 'value' => $item['content']]); ?>
              <div class="uk-text-right">
                <button type="button" onclick="removeTab('<?= $_lang ?>', <?= $n ?>)" class="md-btn md-btn-icon uk-margin-top"><i class="uk-icon-remove"></i> <?= __("Remove this tab") ?></button>
              </div>
            </li>
        <?php endforeach; ?>
        </ul>
<?php endif; ?>
</div>