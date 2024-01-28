<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
?>
<div class="uk-width-large-1-4 uk-width-medium-1-4 uk-width-small-1-1">
    <?php

    function printFooterMenu($menuitem, $self, $index) {
        if (!array_key_exists($index, $menuitem)) {
            return "";
        }
        $data = $menuitem[$index]->children;
        $length = count($data);
        foreach ($data as $index => $menuitem) {
            echo $self->Html->link($menuitem->name, $menuitem->route);
            if ($index + 1 != $length) {
                echo "<br/>";
            }
        }
    }
    ?>
    <p class="uk-text-bold text-uppercase uk-margin-remove  text-lightsilver"><?= $footermenu[0]->name ?></p>
    <p class="uk-margin-top-remove margin-bottom-35 ">
<?php printFooterMenu($footermenu, $this, 0); ?>
    </p>
    <p class="text-uppercase uk-text-bold uk-margin-remove text-lightsilver"><?= $footermenu[2]->name ?></p>
    <p class="uk-margin-top-remove">
<?php printFooterMenu($footermenu, $this, 2); ?>
    </p>
</div>
<div class="uk-width-large-1-4 uk-width-medium-1-4 uk-width-small-1-1">
    <p class="uk-text-bold text-uppercase uk-margin-remove text-lightsilver"><?= $footermenu[3]->name ?></p>
    <p class="uk-margin-top-remove margin-bottom-35 ">
<?php printFooterMenu($footermenu, $this, 3); ?>
    </p>
    <p class="text-uppercase uk-text-bold uk-margin-remove text-lightsilver"><?= $footermenu[4]->name ?></p>
    <p class="uk-margin-top-remove margin-bottom-35">
<?php printFooterMenu($footermenu, $this, 4); ?>
    </p>
    <p class="uk-text-bold text-uppercase uk-margin-remove text-lightsilver"><?= $footermenu[5]->name ?></p>
    <p class="uk-margin-top-remove margin-bottom-35 ">
<?php printFooterMenu($footermenu, $this, 5); ?>
    </p>

</div>
<div class="uk-width-large-1-4 uk-width-medium-1-4 uk-width-small-1-1">
    <p class="text-uppercase uk-text-bold uk-margin-remove text-lightsilver"><?= $footermenu[1]->name ?></p>
    <p class="uk-margin-top-remove">
<?php printFooterMenu($footermenu, $this, 1); ?>
    </p>
    <p class="uk-text-bold text-uppercase uk-margin-remove text-lightsilver">
        <?= $this->Html->link($footermenu[6]->name, $footermenu[6]->route); ?>
    </p>
    <p class="uk-margin-top-remove margin-bottom-35 ">
<?php printFooterMenu($footermenu, $this, 6); ?>
    </p>
    <p class="margin-bottom-35 ">
        <a href="<?= $this->Url->build(['controller' => 'feedbacks', 'action' => 'add']) ?>" class="redbutton text-uppercase text-medium uk-margin-remove">
            <?= __("Гомдол Мэдээлэл") ?>
        </a>
        <a href="<?= $this->AppView->getSiteMeta("shilendans", false) ?>" target="_blank" class="greybutton text-uppercase text-medium  uk-margin-remove">
            <?= __("Шилэн Данс") ?>
        </a>
    </p>
    <p class="uk-text-bold text-white text-uppercase uk-margin-remove text-10">
<?= __("Та манай сайтын") ?>
    </p>
    <h2 class="uk-text-bold text-white  uk-margin-remove" style="line-height: 28px;">
        <?= number_format($counter)  ?>
    </h2>
    <p class="uk-text-bold text-white text-uppercase uk-margin-remove text-10">
<?= __("дахь зочин боллоо.") ?>
    </p>
</div>