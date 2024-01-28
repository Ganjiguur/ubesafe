<%

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
        ->filter(function($field) use ($schema) {
            return !in_array($schema->columnType($field), ['binary', 'text']);
        })
        ->take(7);

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin>
            <div class="uk-float-left">
                <h1><?= __('<%= $pluralHumanName %>') ?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?= __('Шүүлт & хайлт') ?></span>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="md-fab-wrapper">
                    <?= $this->Html->link('<i class="material-icons">add</i>', ['action' => 'add'], ['class' => 'md-fab md-fab-success', 'escapeTitle' => false]) ?>
                </div>      

                <div class="uk-overflow-container">
                    <table class="uk-table ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <% $columns =0; %>
                                <% foreach ($fields as $field): %>
                                    <% $columns++; %>
                                    <%
                                    if (in_array($field, $primaryKey)) {
                                        continue;
                                    }
                                    %>
                                <th><?= $this->Paginator->sort('<%= $field %>', '<span title="' . __("Order by") . ' ' . __('<%= Inflector::humanize($field) %>') . '" data-uk-tooltip="{cls:\'long-text\'}">' . __('<%= Inflector::humanize($field) %>') . '</span>', ['escape' => false]) ?></th>
                                <% endforeach; %>
                                <th class= "uk-text-center" width="50"><?= __('Үйлдэл') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = ($this->Paginator->current('<%= $pluralVar %>') - 1) * $paginatorlimit; ?>
                            <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
                            <tr>
                                <td><?= ++$n ?></td>
                                <%
                                foreach ($fields as $field) {
                                    if (in_array($field, $primaryKey)) {
                                        continue;
                                    }
                                    $isKey = false;
                                    if (!empty($associations['BelongsTo'])) {
                                        foreach ($associations['BelongsTo'] as $alias => $details) {
                                            if ($field === $details['foreignKey']) {
                                                $isKey = true;
                                                %>
                                <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
                                                <%
                                                break;
                                            }
                                        }
                                    }
                                    if ($isKey !== true) {
                                        if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
                                            %>
                                <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
                                            <%
                                        } else {
                                            %>
                                <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
                                            <%
                                        }
                                    }
                                }

                                $pk = '$' . $singularVar . '->' . $primaryKey[0];
                                %>
                                <td>
                                    <div class='uk-button-group'>
                                        <?= $this->Html->link('<i class="md-icon material-icons">remove_red_eye</i>', ['action' => 'view', <%= $pk %>, 'preview' => 'true'], ['escapeTitle' => false, 'title' => __("Харах"), 'target' => '_blank', 'data-uk-tooltip' => '', 'class' => 'uk-display-inline-block']) ?>
                                        <?= $this->Html->link('<i class="md-icon material-icons">edit</i>', ['action' => 'edit', <%= $pk %>],['class' => 'uk-display-inline-block', 'title' => __('Edit'), 'escapeTitle' => false, 'data-uk-tooltip' => '']) ?>
                                        <?= $this->Form->postLink('<i class="md-icon material-icons">delete</i>', ['action' => 'delete', <%= $pk %>], ['class' => 'uk-display-inline-block', 'title' => __('Delete'), 'escapeTitle' => false, 'data-uk-tooltip' => '', 'confirm' => __('Are you sure you want to delete # {0}?', $n)]) ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($<%= $pluralVar %>) == 0): ?>
                            <tr>
                                <td colspan="<% $columns + 2 %>" class="uk-text-center padding-20 uk-text-muted"><?= __('Мэдээлэл байхгүй байна') ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?= $this->element('pagination') ?>
                </div>
            </div>
        </div>
    </div>
</div>
