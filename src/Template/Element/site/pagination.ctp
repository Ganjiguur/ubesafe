<?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()): ?>
    <ul class="uk-pagination margin-top-30" style="position: relative;z-index: 1;">
        <?= $this->Paginator->numbers(['first' => 1, 'last' => 0, 'modulus' => 4]) ?>
        <?= $this->Paginator->next(__("Дараах"), ['escape' => false]) ?>
    </ul>
<?php endif; ?>