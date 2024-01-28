<?php 
/*
 * required variables:
 * entity
 * _name
*/

$revisions = 0;
foreach ($entity->_versions as $version) {
    if ($version->action == "created" || $version->action == "updated") {
        $revisions++;
    }
}

if($revisions > 1) :?>
<div class="uk-form-row">
    <i class="material-icons">history</i> <?= __("Revisions") ?>: 
    <strong><?= $revisions - 1; ?></strong>
    <a href="<?= $this->Url->build(['controller'=>'revisions','action'=>$_name, $entity->id]) ?>" data-uk-tooltip="{cls:'long-text'}" title="Ийм тооны өөрчлөлт орсон байгаа ба, эдгээрээс сонгон сэргээх боломжтой."><?= __("Browse") ?></a>
</div>
<?php endif;?>