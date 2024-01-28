<?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()): ?>
    <ul class="uk-pagination uk-margin-medium-top">
        <?= $this->Paginator->prev('<i class="uk-icon-angle-double-left"></i>', ['escape' => false]) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('<i class="uk-icon-angle-double-right"></i>', ['escape' => false]) ?>
    </ul>
<?php endif; ?>