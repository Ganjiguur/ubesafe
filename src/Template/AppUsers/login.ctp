<?php

use Cake\Core\Configure;

$this->layout = 'login';
?>
<?php $this->loadHelper('Form', ['templates' => 'md_form']); ?>

<style>
  .grecaptcha-badge {
    display: none;
  }
</style>

<div class="md-card-content large-padding">
  <div class="login_heading">
    <div class="amin_logo"></div>
  </div>
  <?= $this->Flash->render() ?>
  <?= $this->Form->create(null, ['autocomplete' => 'off', 'id' => 'login-form']) ?>
  <div class="uk-form-row">
    <label for="login_email"><?= __('Имэйл') ?></label>
    <?= $this->Form->input('email', ['id' => 'login_email', 'required' => true, 'label' => false, 'class' => 'md-input', 'required' => 'required']); ?>
  </div>
  <div class="uk-form-row">
    <label for="login_password"><?= __('Нууц үг') ?></label>
    <?= $this->Form->input('password', ['id' => 'login_password', 'required' => true, 'label' => false, 'class' => 'md-input', 'required' => 'required']); ?>
  </div>
  <div class="uk-margin-medium-top">
    <button class="invisible-recaptcha md-btn md-btn-primary md-btn-block md-btn-large" type="button">
      <?= __('Нэвтрэн орох') ?>
    </button>
  </div>
  <div class="uk-margin-top uk-margin-medium-bottom">
    <?php
    if (Configure::read('Users.Email.required')) {
      echo $this->Html->link(__('Нууц үгээ мартсан?'), ['action' => 'requestResetPassword'], ['class' => 'uk-float-right', 'style' => 'margin-top:2px;']);
    }
    ?>
    <?php $this->Form->unlockField("g-recaptcha-response") ?>
    <?php if (Configure::read('Users.RememberMe.active')): ?>
      <span class="icheck-inline">
        <?=
        $this->Form->input(Configure::read('Users.Key.Data.rememberMe'), [
            'type' => 'checkbox',
            'label' => false,
            'checked' => Configure::read('Users.RememberMe.checked'),
            'id' => 'login_page_stay_signed',
            'data-md-icheck' => ''
        ]);
        ?>
        <label for="login_page_stay_signed" class="inline-label"><?= __(' Намайг сана') ?></label>
      </span>
    <?php endif; ?>
  </div>
  <?= $this->Form->end() ?>
</div>


<?= $this->AppView->addInvisibleReCaptchaScript(); ?>


<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  $('input.md-input').keypress(function(e) {
    var key = e.charCode || e.keyCode || 0;
    if (key === 13) {
        grecaptcha.execute();
    }
});
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>