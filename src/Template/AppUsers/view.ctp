<?php $isAdmin = $this->AppUser->isAdmin(); ?>

<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= __('Хэрэглэгчийн мэдээлэл') ?></h1>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), $this->request->referer(), ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>

    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-6-10">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="md-fab-wrapper">
                            <?= $this->Html->link('<i class="material-icons">edit</i>', ['action' => 'edit'],['class' => 'md-fab md-fab-success', 'escapeTitle'=>false]) ?>
                        </div>   
                        <div class="uk-form-row">
                             <?= $this->Html->link('<i class="uk-icon-lock"></i> &nbsp;' . __('Нууц үг солих'), ['plugin' => null, 'controller' => 'AppUsers', 'action' => 'changePassword'], ['class' => 'md-btn md-btn-wave uk-float-right', 'escape' => false]) ?>
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
                        </div>
                        <div class="uk-form-row">
                            <h4 class="heading_c uk-margin-small-bottom"><?= __("Мэдээлэл") ?></h4>
                            <ul class="md-list md-list-addon">
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">check_circle</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= $AppUsers->active ? __("Идэвхтэй") : __("Идэвхгүй") ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Төлөв') ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">account_circle</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= $AppUsers->username ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Хэрэглэгчийн нэр') ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">accessibility</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= $AppUsers->full_name ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Бүтэн нэр') ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= $AppUsers->email ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Имэйл') ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">security</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= $AppUsers->role ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Эрх') ?></span>
                                    </div>
                                </li>

                                <?php if ($AppUsers->role == 'moderator') : ?>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">playlist_add_check</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading"><?= implode(',', $AppUsers->permission) ?></span>
                                        <span class="uk-text-small uk-text-muted"><?= __('Зөвшөөрөх хэсгүүд') ?></span>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--TODO Show user last activities-->
<!--            <div class="uk-width-medium-4-10">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_c uk-margin-medium-bottom"><?= __('Сүүлийн үйлдлүүд') ?></h3>
                        <div class="uk-form-row">
                           
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
</div>