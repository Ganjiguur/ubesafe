<?php  
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$roles = getRoles();
$this->loadHelper('Form', ['templates' => 'md_form']);
$activeClass = 'md-btn md-btn-small md-btn-primary md-btn-wave';
$defaultClass = 'md-btn md-btn-small md-btn-wave';
$filterBy =  (empty($this->request->pass))?'':$this->request->pass[0]; 
if(empty($roles[$filterBy])) $filterBy = '';
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Хэрэглэгчид') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?=__('Шүүлт & хайлт')?></span>
            </div>
            <div class="uk-float-right" data-uk-margin>
                <?= $this->Form->create('', ['type' => 'get', 'class'=>'uk-search uk-display-inline-block uk-margin-right']) ?>
                    <?= $this->Form->input('searchText', ['class' => "uk-search-field uk-text-truncate", 'label' => false, 'placeholder' => __('Хайх үгээ бичнэ үү'), 'value' => $this->request->query('searchText'), 'type'=>'search']) ?>
                <?= $this->Form->end(); ?>

                <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                    <button class="md-btn md-btn-small md-btn-wave <?= empty($filterBy)?'':'md-btn-primary' ?>"><?= empty($filterBy)?__('Бүгд'):$roles[$filterBy]['text'] ?> <i class="material-icons">keyboard_arrow_down</i></button>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><?= $this->Html->link(__("Бүгд"). (empty($filterBy)? ' <i class="uk-icon-check"></i>' : ''), [null],['escapeTitle'=>false]); ?></li>
                            <?php foreach ($roles as $role=>$value) : ?>
                            <li><?= $this->Html->link($value['text']. (($filterBy == $role) ? ' <i class="uk-icon-check"></i>' : ''), [$role],['escapeTitle'=>false]); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="md-fab-wrapper">
                    <?= $this->Html->link('<i class="material-icons">add</i>', ['action' => 'add'], ['class' => 'md-fab md-fab-success', 'escape' => false]) ?>
                </div>      
            
                <div class="uk-overflow-container">
                    <table class="uk-table uk-table-hover kb-data-table kb-table">
                        <thead>
                            <tr class="sort-header">
                                <th class="uk-text-center"></th>
                                <th style="min-width:200px">
                                    <?= $this->Paginator->sort('username', __('Хэрэглэгчийн нэр')) ?>
                                </th>
                                <?php foreach (availableModules() as $n => $module): ?>
                                <th>
                                    <?= $module['title'];?>
                                </th>
                                <?php endforeach ?>
                                <th class="uk-text-center"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($AppUsers as $user): ?>
                                <tr>
                                    <td class="uk-text-center"><?= ($user->active) ? '<i class="uk-icon-circle uk-text-success"></i>' : '<i class="uk-icon-circle uk-text-danger"></i>' ?></td>
                                    <td>
                                        <?= $this->Html->image($user->photo_url, ["class" => "md-user-image uk-margin-small-right"]); ?>
                                        <?= h($user->username) ?> (<?= h($roles[$user->role]['text']) ?>)
                                    </td>
                                    <?php foreach (availableModules() as $v => $module): ?>
                                    <td>
                                      <?php 
                                      if(isset($user->permission) && is_array($user->permission)) {
                                        $permission = $user->permission;
                                        
                                      } else {
                                        $permission = [];
                                      }
                                      ?>
                                        <?php if($user->role == 'admin' || $user->is_superuser || in_array($v, $permission)): ?>
                                            <i class="uk-icon-check uk-text-success"></i>
                                        <?php else :?>
                                            <i class="uk-icon-close uk-text-danger"></i>
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach ?>
                                    <td class="uk-text-center">
                                        <div class="md-btn-group">
                                        <?= $this->Html->link('<i class="md-icon material-icons">mode_edit</i>', ['action' => 'edit', $user->id], ['escapeTitle' => false, 'title' => __("Засах"), 'data-uk-tooltip'=>'', 'class'=>'uk-display-inline-block']) ?>
                                        <?= $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $user->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', $user->full_name), 'data-uk-tooltip'=>'', 'class'=>'uk-display-inline-block']) ?>
                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                            <?php if(count($AppUsers) ==0):?>
                            <tr>
                                <td colspan="50" class="uk-text-center padding-20 uk-text-muted"><?=__('Мэдээлэл байхгүй байна')?></td>
                            </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                    <?= $this->element('cms/pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</div>