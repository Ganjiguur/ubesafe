<?php $this->layout = 'login';?>
<?php  $this->loadHelper('Form', ['templates' => 'md_form']);?>

<div class="md-card-content large-padding">
    <?= $this->Flash->render() ?>
    <?=$this->Html->link('', ['action'=>'login'], ['class'=>'uk-position-top-right uk-close uk-margin-right uk-margin-top'])?>
    <h2 class="heading_a uk-margin-large-bottom"><?=__('Нууц үгээ мартсан?') ?></h2>
    <?= $this->Form->create(null, ['autocomplete' => 'off']) ?>
        <div class="uk-form-row">
            <label for="login_email_reset"><?=__('Имэйл хаягаа оруулна уу')?></label>
            <?= $this->Form->input('reference', ['class' => 'md-input', 'label'=>false, 'id'=>'login_email_reset']) ?>
        </div>
        <div class="uk-margin-medium-top">
          <?= $this->Form->button(__('Нууц үг сэргээх'), ['type'=>'submit', 'class'=>'md-btn md-btn-primary md-btn-block', 'id'=>'submitBtn']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>