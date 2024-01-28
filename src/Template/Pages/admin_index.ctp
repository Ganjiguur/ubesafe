<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);

$fparam = $this->request->query('fparam');
$searchText = $this->request->query('searchText');
$flang = $this->request->query('flang');
$query = ['fparam' => $fparam, 'searchText' => $searchText, 'flang' => $flang];
$activeClass = 'md-btn md-btn-small md-btn-primary md-btn-wave padding-top-4';
$defaultClass = 'md-btn md-btn-small md-btn-wave padding-top-4';
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Вэб хуудсууд') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Шүүлт & хайлт') ?></span>
            </div>
            <div class="uk-float-right" data-uk-margin>
                <?php
                echo $this->Form->create('', ['type' => 'get', 'class' => 'uk-search uk-display-inline-block uk-margin-right']);
                echo $this->Form->input('searchText', ['class' => "uk-search-field uk-text-truncate", 'label' => false, 'placeholder' => __('Хайх үгээ бичнэ үү'), 'value' => $this->request->query('searchText'), 'type' => 'search']);
                echo $this->Form->end();
                ?>
                <div class="md-btn-group uk-margin-right uk-display-inline-block">
                    <?= $this->Html->link(__("БҮГД"), array_merge($query, ['fparam' => '']), ['class' => ($fparam == '') ? $activeClass : $defaultClass]) ?>
                    <?= $this->Html->link('ИДЭВХТЭЙ', array_merge($query, ['fparam' => 'published']), ['class' => ($fparam == 'published') ? $activeClass : $defaultClass]) ?>
                    <?= $this->Html->link(__("НООРОГ"), array_merge($query, ['fparam' => 'draft']), ['class' => ($fparam == 'draft') ? $activeClass : $defaultClass]) ?>
                    <?= $this->Html->link(__("УСТГАСАН"), array_merge($query, ['fparam' => 'deleted']), ['class' => ($fparam == 'deleted') ? $activeClass : $defaultClass]) ?>
                </div>

                <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                    <button class="md-btn md-btn-small md-btn-wave <?= ($flang != NULL) ? 'md-btn-primary ' : '' ?>"><?= __("ХЭЛ ") . ($flang != NULL ? ' (' . $flang . ')' : '' ) ?> <i class="material-icons">keyboard_arrow_down</i></button>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><?= $this->Html->link(__("Бүгд") . (($flang == null) ? ' <i class="uk-icon-check"></i>' : ''), array_merge($query, ['flang' => '']), ['escapeTitle' => false]); ?></li>
                            <?php foreach (getLanguages() as $key => $lang) : ?>
                                <li><?= $this->Html->link($lang . (($flang == $key) ? ' <i class="uk-icon-check"></i>' : ''), array_merge($query, ['flang' => $key]), ['escapeTitle' => false]); ?></li>
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
                    <table class="uk-table">
                        <thead>
                            <tr class="sort-header">
                                <th><?= __('#') ?></th>                       
                                <th><?= $this->Paginator->sort('title', '<span title="' . __("Гарчигаар эрэмблэх") . '" data-uk-tooltip>' . __("Гарчиг") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('name', '<span title="' . __("Нэрээр эрэмблэх") . '" data-uk-tooltip>' . __("Нэр") . '</span>', ['escape' => false]) ?></th>
                                <th class= "uk-text-center"><?= $this->Paginator->sort('language', '<span title="' . __("Хэлээр эрэмблэх") . '" data-uk-tooltip>' . __("Хэл") . '</span>', ['escape' => false]) ?></th>
                                <th class= "uk-text-center"><?= $this->Paginator->sort('updated_by', '<span title="' . __("Зассан хүний нэрээр эрэмблэх") . '" data-uk-tooltip="{cls:\'long-text\'}">' . __('Зассан') . '</span>', ['escape' => false]) ?></th>
                                <th class="uk-text-center datetime-col"><?= $this->Paginator->sort('modified', '<span title="' . __("Сүүлд зассан огноогоор эрэмблэх") . '" data-uk-tooltip="{cls:\'long-text\'}">' . __('Сүүлд зассан') . '</span>', ['escape' => false]) ?></th>
                                <th class= "uk-text-center" width="50"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = ($this->Paginator->current('Pages') - 1) * $paginatorlimit; ?>
                            <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td><?= ++$n; ?>.</td>
                                    <td>
                                        <?php
                                        echo ($page->status) ? '' : '<i class="uk-icon-sticky-note-o" style="color:orange" data-uk-tooltip title="Ноорог хуудас"></i> ';
                                        echo h($page->title) ?: __("Гарчиггүй");
                                        ?>
                                        <br>
                                        <?= $this->Html->link('/'.$page->slug, $page->previewUrl, ['title' => __("Page slug"), 'target' => '_blank', 'data-uk-tooltip' => '']) ?>
                                    </td>
                                    <td><?= h($page->name) ?: __("Нэргүй") ?></td>
                                    <td class="uk-text-center"><?= $page->language ?></td>

                                    <td class="uk-text-center"><?= h($page->user->username) ?></td>
                                    <td class="uk-text-center"><?= getDateString($page->modified) ?></td>
                                    <td class="uk-text-center">
                                        <div class="md-btn-group">
                                            
                                            <?php 
                                            if($page->archived) {
                                                echo $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $page->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', h($page->title)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 

                                                echo $this->Form->postLink('<i class="md-icon material-icons">restore</i>', ['action' => 'restore', $page->id], ['escapeTitle' => false, 'title' => __("Сэргээх"), 'confirm' => __('Та "{0}"-ийг сэргээхдээ итгэлтэй байна уу?', $page->title), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 
                                            } else {
                                                echo $this->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'edit', $page->id], ['escapeTitle' => false, 'title' => __("Засах"), 'data-uk-tooltip' => __("Засах"), 'class' => 'uk-display-inline-block']);

                                                echo $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'archive', $page->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', h($page->title)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($pages) == 0): ?>
                                <tr>
                                    <td colspan="20" class="uk-text-center padding-20 uk-text-muted"><?= __('Мэдээлэл байхгүй байна') ?></td>
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
