<?php
use Cake\Utility\Text;

require_once(ROOT . DS . 'plugins' . DS . "tools.php");

echo $this->Html->css(getUikitCssPath('components/search.almost-flat.min'), ['block' => true]);

$this->loadHelper('Form', ['templates' => 'md_form']);
  
$qType = isset($types[$this->request->query('type')]) ? $this->request->query('type') : NULL;
$searchText = $this->request->query('searchText');
$query = ['type' => $qType, 'searchText' => $searchText];
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('Banners') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Шүүлт & хайлт') ?></span>
            </div>
            <div class="uk-float-right" data-uk-margin>
                <?php
                echo $this->Form->create('', ['type' => 'get', 'class' => 'uk-search uk-display-inline-block uk-margin-right']);
                echo $this->Form->input('searchText', ['class' => "uk-search-field uk-text-truncate", 'label' => false, 'placeholder' => __('Хайх үгээ бичнэ үү'), 'value' => $this->request->query('searchText'), 'type' => 'search']);
                echo $this->Form->end();
                ?>
                
                <div class="uk-button-dropdown uk-margin-right" data-uk-dropdown="{mode:'click'}">
                    <button class="md-btn md-btn-small md-btn-wave <?= ($qType != NULL) ? 'md-btn-primary ' : '' ?>"><?= __("ТӨРӨЛ") . ($qType == NULL ? ''  : ' (' . $types[$qType] . ')') ?> <i class="material-icons">keyboard_arrow_down</i></button>
                    <div class="uk-dropdown uk-dropdown-small uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <?php $types = ['' => __("БҮГД")] + $types ?>
                            <?php foreach ($types as $k => $v) : ?>
                                <li>
                                <?= $this->Html->link($v . (($k == $qType) ? ' <i class="uk-icon-check"></i>' : ''), array_merge($query, ['type' => $k]), ['escapeTitle' => false]); ?>
                                </li>
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
                    <?= $this->Html->link('<i class="material-icons">add</i>', ['action' => 'crud'], ['class' => 'md-fab md-fab-success', 'escapeTitle' => false]) ?>
                </div>      

                <div class="uk-overflow-container">
                    <table class="uk-table">
                        <thead>
                            <tr class="sort-header">
                                <th>#</th>
                                <th width="10"><?= $this->AppView->getStatusHeader('active'); ?></th>
                                <th><?= __("Banner") ?></th>
                                <th><?= __("Locations") ?></th>
                                <th><?= __("Info") ?> <i class="uk-icon-question-circle" title="Type, Language, Duration, Link" data-uk-tooltip="{cls:'long-text'}"></i></th>
                                <th><?= __("Detail") ?> <i class="uk-icon-question-circle" title="Title, Subtitle" data-uk-tooltip="{cls:'long-text'}"></i></th>
                                <th class= "uk-text-center" width="50"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = ($this->Paginator->current('banners') - 1) * $paginatorlimit; ?>
                            <?php foreach ($banners as $banner): ?>
                                <tr class="<?= $banner->isActive ? 'bg-success' : '' ?>">
                                    <td width="10"><?= ++$n ?></td>
                                    <td width="10"><?= $this->AppView->getStatusIndicator($banner->active); ?></td>
                                    <td style="width:200px">
                                        <?php
                                        switch ($banner->media_type) {
                                            case 'image':
                                                echo $this->Html->image($banner->media, ['style'=> 'max-width:200px']);
                                                break;
                                            default:
                                                break; 
                                        } ?>
                                    </td>
                                    <td style="width:250px">
                                        <?php foreach($banner->pages ?: [] as $page): ?>
                                            <?= __("Page") ?>: <?= $this->Html->link(Text::truncate(h($page->name), 30), $page->previewUrl, ['target' => '_blank']) ?>
                                        <br>
                                        <?php endforeach; ?>
                                        <?php foreach($banner->products ?: [] as $product): ?>
                                            <?= __("Product") ?>: <?= $this->Html->link(Text::truncate(h($product->title), 30), $product->previewUrl, ['target' => '_blank']) ?>
                                        <br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td style="width:200px">
                                        <strong><?= __("Type") ?>:</strong> 
                                        <?= $banner->typeLocale ?>
                                        <br>
                                        <strong><?= __("Language") ?>: </strong>
                                        <?= $banner->language ?>
                                        <br>
                                        <strong><?= __("Duration") ?>: </strong>
                                        <?php if ($banner->never_end): ?>
                                            <?= __("Never end") ?>
                                        <?php else: ?>
                                            <?= getDateString($banner->startdate, true) . ' - ' . getDateString($banner->enddate, true) ?>
                                        <?php endif; ?>
                                        <br>
                                        <strong><?= __("Link") ?>: </strong>
                                        <?= $this->Html->link(Text::truncate($banner->link, 30), $banner->link, ['target' => '_blank']) ?>
                                    </td>
                                    <td>
                                        <strong><?= h($banner->title) ?></strong>
                                        <br>
                                        <?= strip_tags($banner->subtitle)  ?>
                                    </td>
                                    <td>
                                        <div class='uk-button-group'>
                                            <?= $this->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'crud', $banner->id], ['class' => 'uk-display-inline-block', 'title' => __('Edit'), 'escapeTitle' => false, 'data-uk-tooltip' => '']) ?>
                                            <?= $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', $banner->id], ['class' => 'uk-display-inline-block', 'title' => __('Delete'), 'escapeTitle' => false, 'data-uk-tooltip' => '', 'confirm' => __('Are you sure you want to delete # {0}?', $n)]) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($banners) == 0): ?>
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