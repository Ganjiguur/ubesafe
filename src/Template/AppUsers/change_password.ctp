<?php 
$this->loadHelper('Form', ['templates' => 'md_form']);
if (!$validatePassword) {
    $this->layout = 'login';
}
?>

<?php if ($validatePassword) : ?>
<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <h1><?=__('Нууц үгээ солих')?></h1>
        <span class="uk-text-muted uk-text-upper uk-text-small"><?=__('Шинэ нууц үгээ оруулна уу')?></span>
    </div>
    <div id="page_content_inner">
        <div class="uk-grid">
            <div class="uk-width-large-1-3 uk-width-medium-1-2">
                <div class="md-card">
                    <div class="md-card-content">
                        <?= $this->Flash->render() ?>
                        <?= $this->Form->create($user) ?>
                        <?php if ($validatePassword) : ?>
                            <div class="uk-form-row">
                                <label><?=__('Одоогийн нууц үг')?></label>
                                <?=
                                $this->Form->input('current_password', [
                                    'type' => 'password',
                                    'required' => true,
                                    'label' => false,
                                    'class' => 'md-input']);
                                ?>
                            </div>
                            <?php endif; ?>
                        <div class="uk-form-row">
                            <label><?=__('Шинэ нууц үг')?></label>
                            <?= $this->Form->input('password', ['label' => false, 'type' => 'password', 'required' => true, 'class' => 'md-input', 'error'=>false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?=__('Нууц үгээ давт')?></label>
                            <?= $this->Form->input('password_confirm', ['label' => false, 'type' => 'password', 'required' => true, 'class' => 'md-input']);?>
                        </div>
                        
                        <?= $this->Form->error('password') ?>
                        <div class="uk-form-row uk-margin-top uk-margin-bottom">
                            <?= $this->Form->button(__d('Users', 'Хадгалах'), ['class' => 'md-btn md-btn-primary md-btn-block md-btn-wave-light']); ?>
                        </div>
                        
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<?php else: ?>

<div class="md-card-content large-padding">
    <div class="login_heading">
        <div class="amin_logo"></div>
    </div>
    <?= $this->Flash->render() ?>
    
    <?= $this->Form->create($user) ?>
    <?php if ($validatePassword) : ?>
        <div class="uk-form-row">
            <label><?=__('Одоогийн нууц үг')?></label>
            <?=
            $this->Form->input('current_password', [
                'type' => 'password',
                'required' => true,
                'label' => false,
                'class' => 'md-input']);
            ?>
        </div>
        <?php endif; ?>
    <div class="uk-form-row">
        <label><?=__('Шинэ нууц үг')?></label>
        <?= $this->Form->input('password', ['label' => false, 'type' => 'password', 'required' => true, 'class' => 'md-input', 'error'=>false]); ?>
    </div>
    <div class="uk-form-row">
        <label><?=__('Нууц үгээ давт')?></label>
        <?= $this->Form->input('password_confirm', ['label' => false, 'type' => 'password', 'required' => true, 'class' => 'md-input']);?>
    </div>

    <?= $this->Form->error('password') ?>
    <div class="uk-form-row uk-margin-top uk-margin-bottom">
        <?= $this->Form->button(__d('Users', 'Хадгалах'), ['class' => 'md-btn md-btn-primary md-btn-block md-btn-wave-light']); ?>
    </div>

    <?= $this->Form->end() ?>
</div>
<?php endif; ?>