<?= $this->Html->css('/cms/css/cropper', ['block' => true]); ?>
<?= $this->Html->css('/cms/css/components/form-file.almost-flat.min', ['block' => true]); ?>
<?php  
$this->loadHelper('Form', ['templates' => 'md_form']);
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$roles = [];
foreach (getRoles() as $role => $value) {
    $roles[$role]=$value['text'];
}
$this->Form->unlockField('selectize_permission');
?>
<?php $isAdmin = $this->AppUser->isAdmin(); ?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('Хэрэглэгчийн мэдээлэл')?></h1>
                <span class="uk-text-muted uk-text-upper uk-text-small"><?=__('Мэдээлэл шинэчлэх')?></span>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action' => 'index'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>

    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <?= $this->Form->create($AppUsers, ['class' => 'uk-margin-large-bottom', 'type' => 'file']) ?> 
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-7-10">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="md-fab-wrapper">
                            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success']) ?>
                        </div>   
                        <div class="uk-form-row">
                            <?php
                            if (isset($AppUsers->photo) && $AppUsers->photo != "") {
                                $hasPhoto = true;
                            } else {
                                $hasPhoto = false;
                            }
                            ?>
                            <div id='imagePreview'>
                                <?= $this->Html->image($AppUsers->photo_url, ['width' => '200', 'id' => 'photo', "class" => "profileimg"]); ?>
                            </div>
                            <div id='warningTooBig' class="uk-text-danger uk-margin" hidden>
                                <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                                <?= __("Image is it too big. Please resize it down first (must be less than 3300x3300)") ?>
                            </div>
                            
                            <div id='warningSize' class="uk-text-danger uk-margin" hidden>
                                <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                                <?= __("Image size must be less than 3 MB") ?>
                            </div>
                            <div id='warningHeight' class="uk-text-danger uk-margin" hidden>
                                <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                                <?= __("Image dimension must be more thant 300x300") ?>
                            </div>
                            <button id="deleteButton" type="button" class="md-btn md-btn-wave uk-margin-top md-btn-icon" <?= $hasPhoto ? '' : 'style="display:none"' ?>><i class="uk-icon-trash"></i> <?= __("Delete photo") ?></button>
                            <div id="addPhoto" class="uk-margin-top" <?= $hasPhoto ? 'style="display:none"' : '' ?>>
                                
                                <a class="uk-form-file md-btn md-btn-primary md-btn-wave uk-text-middle" style="margin-top: -2px;">
                                    <i class="uk-icon-cloud-upload uk-margin-small-right"></i> 
                                    <?= __("Зураг сонгох") ?>
                                    <?= $this->Form->input('user_photo', ['type' => 'file', 'label' => false]); ?>
                                </a>
                            </div>
                            <?= $this->Form->hidden('delete_photo', ['value' => 'false']); ?>
                            <?php $this->Form->unlockField('delete_photo'); ?>

                            <?= $this->Form->hidden('x_photo', ['id' => 'x-photo']) ?>
                            <?= $this->Form->hidden('y_photo', ['id' => 'y-photo']) ?>
                            <?= $this->Form->hidden('width_photo', ['id' => 'width-photo']) ?>
                            <?= $this->Form->hidden('height_photo', ['id' => 'height-photo']) ?>
                            <?= $this->Form->hidden('preview_ratio', ['id' => 'preview-ratio']) ?>
                            <?php $this->Form->unlockField('x_photo'); ?>
                            <?php $this->Form->unlockField('y_photo'); ?>
                            <?php $this->Form->unlockField('width_photo'); ?>
                            <?php $this->Form->unlockField('height_photo'); ?>
                            <?php $this->Form->unlockField('preview_ratio'); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Хэрэглэгчийн нэр') ?></label>
                            <?= $this->Form->input('username', ['class' => 'md-input md-input uk-form-width-large', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Бүтэн нэр') ?></label>
                            <?php echo $this->Form->input('full_name', ['class' => 'md-input md-input uk-form-width-large', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Имэйл') ?></label>
                            <?php echo $this->Form->input('email', ['class' => 'md-input md-input uk-form-width-large', 'label' => false]); ?>
                        </div>
                    </div>
                </div>
            </div>
             <div class="uk-width-medium-3-10">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_c uk-margin-medium-bottom"><?=__('Бусад тохиргоо')?></h3>
                        <div class="uk-form-row">
                            <?=$this->Form->checkbox('active', ['data-switchery'=>'', 'id'=>'active_check'])?>
                            <label for="active_check" class="inline-label"><?=__('User Active')?></label>
                        </div>
                        <hr class="md-hr">
                        <?php if ($isAdmin) : ?>
                            
                            <div class="uk-form-row">
                                <label><?= __('Эрх') ?></label>
                                <?php echo $this->Form->input('role', ['class' => 'md-input', 'label' => false, 'options'=>$roles]); ?>
                            </div>
                            
                            <div class="uk-form-row">
                                <label class="inline-label margin-bottom-6 uk-display-block uk-text-small label-gray"><?= __('Зөвшөөрөх хэсгүүд') ?></label>
                                <?php echo $this->Form->input('permission', ['label' => false, 'options'=>  getModules(), 'multiple'=>true, 'placeholder'=>__('Сонгоно уу...')]); ?>
                            </div>
                            <div class="uk-form-row">
                                <label><?= __("Шинэ нууц үг") .' (' .__('солихгүй бол хоосон орхино') .')' ?></label>
                                <?= $this->Form->input('password_update', ['class' => 'md-input', 'type' => 'password', 'label' => false ]); ?>
                                <?php if ($this->Form->isFieldError('password')) echo $this->Form->error('password'); ?>
                            </div>
                            <div class="uk-form-row">
                                <label><?= __('Confirm new password') ?></label>
                                <?= $this->Form->input('password_update_confirm', ['class' => 'md-input', 'type' => 'password', 'label' => false]); ?>
                            </div>
                        <?php else: ?>
                            <div class="uk-form-row">
                                <?= $this->Html->link('<i class="uk-icon-lock"></i> &nbsp;' . __('Нууц үг солих'), ['plugin'=>null,'controller' => 'AppUsers', 'action' => 'changePassword'], ['class' => 'md-btn md-btn-wave', 'escape' => false]) ?>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
        
<?= $this->element("users/user_form_js") ?>
<?= $this->Html->script('/cms/js/cropper.min', ['block' => true]); ?>
<?= $this->element("cms/user_photo_js") ?>
