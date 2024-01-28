<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);

$from = $this->request->query('from');
$to = $this->request->query('to');
$searchText = $this->request->query('searchText');
?>

<style>
  .inline-form div {
    display: inline-block;
    width: 120px;
    margin-right: 20px;
  }
  #filter_form > :first-child {
    width:200px;
  }
</style>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Ажлын анкет') ?> (<?= $this->Paginator->counter('{{count}}'); ?>)</h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Шүүлт & хайлт') ?></span>
            </div>
          <div class="uk-float-right" style="margin-right:20px;">
            <?php 
              echo $this->Form->create('', ['type' => 'get', 'class' => 'uk-form uk-display-inline-block inline-form', 'id' => 'filter_form']);
              echo $this->Form->input('searchText', ['class' => "md-input ", 'label' => false, 'placeholder' => __('Овог, нэр, ажлын байр'), 'value' => $searchText, 'type' => 'search']);
              
              echo $this->Form->input('from', ['label' => false, 'placeholder' => __("Эхлэх огноо"), 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY-MM-DD'}", 'class' => 'md-input', 'required' => false, 'value' => $from]);
              
              echo $this->Form->input('to', ['label' => false, 'placeholder' => __("Дуусах огноо"), 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY-MM-DD'}", 'class' => 'md-input', 'required' => false, 'value' => $to]);
            ?>
            <div class="submit">
              <?= $this->Form->button("Filter", ['class'=>'md-btn md-btn-small md-btn-wave']) ?>
            </div>
            <?php echo $this->Form->end();?>
            <?= $this->Form->button("Export to excel", ['class'=>'md-btn md-btn-small md-btn-wave md-btn-primary', 'name' => 'action', "data-uk-modal"=>"{target:'#modal_export'}"]) ?>
          </div>
        </div>
    </div>
    <div id="page_content_inner">
<?= $this->Flash->render() ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                    <table class="uk-table ">
                        <thead>
                            <tr class="sort-header">
                                <th><?= __('#') ?></th>
                                <th><?= $this->Paginator->sort('lastName', '<span title="' . __("Овгоор эрэмблэх") . '" data-uk-tooltip>' . __("Овог") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('firstName', '<span title="' . __("Нэрээр эрэмблэх") . '" data-uk-tooltip>' . __("Нэр") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('contactEmail', '<span title="' . __("Имэйлээр эрэмблэх") . '" data-uk-tooltip>' . __("Имэйл") . '</span>', ['escape' => false]) ?></th>
                                <th class="datetime-col"><?= $this->Paginator->sort('created', '<span title="' . __("Ирсэн огноогоор эрэмблэх") . '" data-uk-tooltip="{cls:\'long-text\'}">' . __("Огноо") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('wantedJob', '<span title="' . __("Эрэмблэх") . '" data-uk-tooltip>' . __("Сонирхож буй ажлын байр") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('possibleEmploymentDate', '<span title="' . __("Эрэмблэх") . '" data-uk-tooltip>' . __("Ажилд орох боломжтой огноо") . '</span>', ['escape' => false]) ?></th>
                                <th class= "uk-text-center" width="50"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = ($this->Paginator->current('Applications') - 1) * $paginatorlimit; ?>
                            <?php foreach ($applications as $anket): ?>
                                <tr>
                                    <td><?= ++$n ?></td>
                                    <td><?= h($anket->lastName) ?></td>
                                    <td><?= h($anket->firstName) ?></td>
                                    <td><?= h($anket->contactEmail) ?></td>
                                    <td><?= getDateString($anket->created) ?></td>
                                    <td>
                                      <strong><?= h($anket->wantedJob) ?></strong>
                                    </td>
                                    <td><?= h($anket->possibleEmploymentDate) ?></td>
                                    <td class="uk-text-center">
                                        <div class="md-btn-group">
                                            <?php
                                            echo $this->Html->link('<i class="md-icon material-icons">remove_red_eye</i>', ['action' => 'view', $anket->id], ['escapeTitle' => false, 'title' => __("Харах"), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block', 'target' => '_blank']);
                                            echo $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $anket->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', h($anket->firstName)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']);  
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($applications) == 0): ?>
                                <tr>
                                    <td colspan="20" class="uk-text-center padding-20 uk-text-muted"><?= __('Мэдээлэл байхгүй байна') ?></td>
                                </tr>
                    <?php endif; ?>
                        </tbody>
                    </table>
<?= $this->element('cms/pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="uk-modal" id="modal_export">
    <div class="uk-modal-dialog">
        <button type="button" class="uk-modal-close uk-close"></button>
        
        <h3 class="">Export to excel</h3>
        <?php 
              echo $this->Form->create('', ['url' => ['action' => 'toExcel'], 'type' => 'get', 'class' => 'uk-form uk-display-inline-block inline-form']);
              echo $this->Form->input('from', ['label' => false, 'placeholder' => __("Эхлэх огноо"), 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY-MM-DD'}", 'class' => 'md-input', 'required' => true, 'value' => $from]);
              
              echo $this->Form->input('to', ['label' => false, 'placeholder' => __("Дуусах огноо"), 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY-MM-DD'}", 'class' => 'md-input', 'required' => true, 'value' => $to]);
            ?>
            <?= $this->Form->submit("Export", ['class'=>'uk-hidden', 'id'=>'export_submit']) ?>
        
          <?php echo $this->Form->end();?>
        <div class="uk-text-right">
          <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
          <label for="export_submit" class="md-btn md-btn-flat md-btn-flat-primary">Export</label>
        </div>
    </div>
</div>