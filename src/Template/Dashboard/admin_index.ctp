<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);

$actionVerbs = [
    "created" => "үүсгэсэн байна.",
    "updated" => "засварласан байна.",
    "archived" => "архивласан байна.",
    "unarchived" => "архиваас сэргээсэн байна.",
    "deleted" => "устгасан байна.",
];

$models = [
    "AppUsers" => 'хэрэглэгч',
    "Menus" => 'цэс',
    "Extends" =>  'сайтын мэдээлэл',
    "Categories" => 'категори',
    "Articles" => 'нийтлэл',
    "Pages" => 'вэб хуудас',
    "Banners" => 'баннер',
];

if(isset($users)) {
  $filterBy = (empty($this->request->pass)) ? '' : $this->request->pass[0];
  $searchText = $this->request->query('searchText');
  $query = [$filterBy, 'searchText' => $searchText];
  $activeClass = 'md-btn md-btn-small md-btn-primary md-btn-wave padding-top-4';
  $defaultClass = 'md-btn md-btn-small md-btn-wave padding-top-4';

  $selectedUserName = NULL;
  foreach ($users as $user) {
    if ($filterBy == $user->id) {
      $selectedUserName = $user->full_name;
    }
  }
}

?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('User log') ?></h1>
            </div>
            <?php if(isset($users)): ?>
            
          <div class="uk-float-right" data-uk-margin>
                <?php
                echo $this->Form->create('', ['type' => 'get', 'class' => 'uk-search uk-display-inline-block uk-margin-right']);
                echo $this->Form->input('searchText', ['class' => "uk-search-field uk-text-truncate", 'label' => false, 'placeholder' => __('Хайх үгээ бичнэ үү'), 'value' => $this->request->query('searchText'), 'type' => 'search']);
                echo $this->Form->end();
                ?>

                <div class="uk-button-dropdown uk-margin-right" data-uk-dropdown="{mode:'click'}">
                    <button class="md-btn md-btn-small md-btn-wave <?= ($selectedUserName != NULL) ? 'md-btn-primary ' : '' ?>"><?= __("ХЭРЭГЛЭГЧ ") . ($selectedUserName != NULL ? ' (' . $selectedUserName . ')' : '' ) ?> <i class="material-icons">keyboard_arrow_down</i></button>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <?php $userQuery = $query; $userQuery[0] = ''; ?>
                            <li><?= $this->Html->link(__("Бүгд") . (($filterBy == '') ? ' <i class="uk-icon-check"></i>' : ''), $userQuery, ['escapeTitle' => false]); ?></li>
                            <?php foreach ($users as $user) : ?>
                                <?php $userQuery[0] = $user->id ?>
                                <li><?= $this->Html->link($user->full_name . (($filterBy == $user->id) ? ' <i class="uk-icon-check"></i>' : ''), $userQuery, ['escapeTitle' => false]); ?></li>
<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
          <?php endif; ?>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                    <table class="uk-table">
                        <thead>
                            <tr class="sort-header">
                                
                                <th><?= __("Хэзээ") ?></th>
                                <th><?= __("Хэн") ?></th>
                                <th><?= __("Үйл явдал") ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
//                            $n = ($this->Paginator->current() - 1) * $paginatorlimit;
                            foreach ($logs as $log):
                            $modelName = isset($models[$log['model']]) ? $models[$log['model']] : NULL;
                            if ($modelName == NULL) { continue; }
                            ?>
                            <tr>
                                
                                <td>
                                    <?= getDateString($log['date']) ?>
                                </td>
                                <td>
                                    <?= $this->Html->link($log['username'], ['controller' => 'AppUsers', 'action' => 'edit', $log['user_id']]) ?>
                                </td>
                                <td>
                                    <?php 
                                    if($log['action'] == 'created') {
                                        echo 'Шинэ ';
                                    } else {
                                        $title = "";
                                        foreach ($titleFields as $_field) {
                                            $ind = array_search($_field, $log['fields']);
                                            if ($ind !== false) {
                                                $title =  $log['values'][$ind];
                                                break;
                                            }
                                        }
                                        if ($title == "") {
                                            $title = "(id: " . $log['model_id'] .")";
                                        }
                                        echo '"<strong title="id: ' . $log['model_id'] . '" data-uk-tooltip>' . h($title) . '</strong>" ';
                                    }
                                    echo mb_strtolower($modelName) . ' ';
                                    
                                    if($log['action'] !== 'created' && $log['status_changed'] && isset($log['status_action'])) {
                                        echo $log['status_action'];
                                    } else {
                                        echo $actionVerbs[$log['action']];
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($logs) == 0): ?>
                                <tr>
                                    <td colspan="3" class="uk-text-center padding-20 uk-text-muted"><?= __('Мэдээлэл байхгүй байна') ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                    </table>
                    <?= $this->element('cms/pagination') ?>
                </div>
            </div>
        </div>
    </div>
</div>