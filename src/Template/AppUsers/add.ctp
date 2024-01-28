<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$roles = [];
foreach (getRoles() as $role => $value) {
    $roles[$role]=$value['text'];
}

$this->loadHelper('Form', ['templates' => 'md_form']);
?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('Шинэ хэрэглэгч')?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?=__('Шинэ хэрэглэгч үүсгэх')?></span>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), $this->request->referer(), ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="uk-grid">
            <div class="uk-width-large-1-2 uk-width-medium-2-3">
                <div class="md-card">
                    <div class="md-card-content">
                        <?= $this->Form->create($AppUsers, ['class' => 'uk-margin-large-bottom']) ?> 
                        <div class="md-fab-wrapper">
                            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success']) ?>
                        </div>   
                        <div class="uk-form-row">
                            <label><?= __('Хэрэглэгчийн нэр') ?></label>
                            <?= $this->Form->input('username', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Бүтэн нэр') ?></label>
                            <?php echo $this->Form->input('full_name', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Имэйл') ?></label>
                            <?php echo $this->Form->input('email', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Нууц үг') ?></label>
                            <?php echo $this->Form->input('password', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Эрх') ?></label>
                            <?php echo $this->Form->input('role', ['class' => 'md-input', 'label' => false, 'options'=>$roles]); ?>
                        </div>
                        <div class="uk-form-row">
                            <?=$this->Form->checkbox('active', ['data-switchery'=>'', 'id'=>'active_check', 'checked'])?>
                            <label for="active_check" class="inline-label">Active</label>
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>