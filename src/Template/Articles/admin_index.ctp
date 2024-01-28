<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);

$filterBy = (empty($this->request->pass)) ? '' : $this->request->pass[0];
$fparam = $this->request->query('fparam');
$searchText = $this->request->query('searchText');
$flang = $this->request->query('flang');
$query = [$filterBy, 'fparam' => $fparam, 'searchText' => $searchText, 'flang' => $flang];
$activeClass = 'md-btn md-btn-small md-btn-primary md-btn-wave padding-top-4';
$defaultClass = 'md-btn md-btn-small md-btn-wave padding-top-4';

$selectedCatName = NULL;
foreach ($categories as $cat) {
    if ($filterBy == $cat->slug)
        $selectedCatName = $cat->name;
}

?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Нийтлэлүүд') ?></h1>
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
                    <?= $this->Html->link(__("ОНЦЛОХ"), array_merge($query, ['fparam' => 'featured']), ['class' => ($fparam == 'featured') ? $activeClass : $defaultClass]) ?>
                    <?= $this->Html->link(__("НООРОГ"), array_merge($query, ['fparam' => 'draft']), ['class' => ($fparam == 'draft') ? $activeClass : $defaultClass]) ?>
                    <?= $this->Html->link(__("УСТГАСАН"), array_merge($query, ['fparam' => 'deleted']), ['class' => ($fparam == 'deleted') ? $activeClass : $defaultClass]) ?>
                </div>

                <div class="uk-button-dropdown uk-margin-right" data-uk-dropdown="{mode:'click'}">
                    <button class="md-btn md-btn-small md-btn-wave <?= ($selectedCatName != NULL) ? 'md-btn-primary ' : '' ?>"><?= __("КАТЕГОРИ ") . ($selectedCatName != NULL ? ' (' . $selectedCatName . ')' : '' ) ?> <i class="material-icons">keyboard_arrow_down</i></button>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <?php $catQuery = $query; $catQuery[0] = ''; ?>
                            <li><?= $this->Html->link(__("Бүгд") . (($filterBy == '') ? ' <i class="uk-icon-check"></i>' : ''), $catQuery, ['escapeTitle' => false]); ?></li>
                            <?php foreach ($categories as $cat) : ?>
                                <?php $catQuery[0] = $cat->slug ?>
                                <li><?= $this->Html->link($cat->name . (($filterBy == $cat->slug) ? ' <i class="uk-icon-check"></i>' : ''), $catQuery, ['escapeTitle' => false]); ?></li>
<?php endforeach; ?>
                        </ul>
                    </div>
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
                    <table class="uk-table ">
                        <thead>
                            <tr class="sort-header">
                                <th><?= __('#') ?></th>
                                <th><?= $this->Paginator->sort('title', '<span title="' . __("Гарчигаар эрэмблэх") . '" data-uk-tooltip>' . __("Гарчиг") . '</span>', ['escape' => false]) ?></th>
                                <th><?= $this->Paginator->sort('language', '<span title="' . __("Хэлээр эрэмблэх") . '" data-uk-tooltip>' . __("Хэл") . '</span>', ['escape' => false]) ?></th>
                                <?php /*
                                <th class="uk-text-center"><?= $this->Paginator->sort('view', '<i class="material-icons" title="' . __("Үзсэн тоогоор эрэмблэх") . '" data-uk-tooltip>remove_red_eye<i/>', ['escape' => false]) ?></th>
                                <th class="uk-text-center"><?= $this->Paginator->sort('comments', '<i class="material-icons" title="' . __("Сэтгэгдлийн тоогоор эрэмблэх") . '" data-uk-tooltip="{cls:\'long-text\'}">comment<i/>', ['escape' => false]) ?></th>
                                 * 
                                 */?>
                                <th class=""><?= __('Категори') ?></th>
                                <th class="uk-text-center"><?= $this->Paginator->sort('user_id', __("Нийтлэгч")) ?></th>
                                <th class="uk-text-center datetime-col"><?= $this->Paginator->sort('created', '<span title="' . __("Огноогоор эрэмблэх") . '" data-uk-tooltip>' . __("Огноо") . '</span>', ['escape' => false]) ?></th>
                                <th class= "uk-text-center" width="50"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = ($this->Paginator->current('Articles') - 1) * $paginatorlimit; ?>
<?php foreach ($articles as $key => $article): ?>
                                <tr>
                                    <td><?= ++$n ?></td>
                                    <td class="">
                                        <?php
                                        //featured icon
                                        echo ($article->featured) ? '<i class="uk-icon-trophy" style="color:orange" data-uk-tooltip title="Онцлох мэдээ"></i> ' : '';
                                        //draft icone
                                        echo ($article->published) ? '' : '<i class="uk-icon-sticky-note-o" style="color:orange" data-uk-tooltip title="Ноорог мэдээ"></i> ';
                                        echo h($article->title) ?: __("Гарчиггүй");
                                        ?>
                                        <br>
                                        <?= $this->Html->link('/'.$article->slug, $article->previewUrl, ['title' => __("Article slug"), 'target' => '_blank', 'data-uk-tooltip' => '']) ?>
                                    </td>
                                    <td class="uk-text-center"><?= $article->language ?></td>
                                    <?php /*
                                    <td class="uk-text-center"><?= $article->view ?></td>
                                    <td class="uk-text-center"><?= $article->comments ?></td>
                                     * 
                                     */?>
                                    <td class="uk-text-small"><?= implode(', ', array_map(function($cat) {
                                                    return $cat->name;
                                                }, $article->categories)); ?></td>
                                    <td class="uk-text-center">
    <?= $article->user ? $this->Html->link($article->user->username, ['controller' => 'users', 'action' => 'view', $article->user->id]) : '-' ?>
                                    </td>
                                    <td class="uk-text-center"><?= getDateString($article->created) ?></td>
                                    <td class="uk-text-center">
                                        <div class="md-btn-group">
                                            <?php 
                                            if($article->archived) {
                                                echo $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $article->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', h($article->title)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 

                                                echo $this->Form->postLink('<i class="md-icon material-icons">restore</i>', ['action' => 'restore', $article->id], ['escapeTitle' => false, 'title' => __("Сэргээх"), 'confirm' => __('Та "{0}"-ийг сэргээхдээ итгэлтэй байна уу?', h($article->title)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 
                                            } else {
                                                echo $this->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'edit', $article->id], ['escapeTitle' => false, 'title' => __("Засах"), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']);

                                                echo $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'archive', $article->id], ['escapeTitle' => false, 'title' => __("Устгах"), 'confirm' => __('Та "{0}"-ийг устгахдаа итгэлтэй байна уу?', h($article->title)), 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']); 
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
<?php endforeach; ?>
                            <?php if (count($articles) == 0): ?>
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
