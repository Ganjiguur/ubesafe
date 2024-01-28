<?php
$this->loadHelper('Form', ['templates' => 'md_form']);
?>
<!--TODO Product deer zuvhun block, page deer block cover, home page deer featured, cover, block haragdah bolomjtoi talaar tailbar oruulah-->
<div id="page_content">
    <div id="page_heading" data-uk-sticky="{ top: 48, media: 960 }">
        <div class="uk-clearfix" data-uk-margin="">
            <div class="uk-float-left">
                <h1><?= $title_for_page ?></h1>
            </div>
            <div class="uk-float-right">
                <?= $this->Html->link('<i class="uk-icon-angle-left uk-icon-small"></i> &nbsp;' . __('Буцах'), ['action' => 'index'], ['class' => 'md-btn md-btn-flat md-btn-wave', 'escape' => false]) ?>
            </div>
        </div>
    </div>
    <div id="page_content_inner">
        <?= $this->Flash->render() ?>
        <?= $this->Form->create($banner, ['type' => 'file']) ?>
        <?php $this->Form->unlockField('fields') ?>
        <div class="md-fab-wrapper">
            <?= $this->Form->button('<i class="material-icons">check</i>', ['class' => 'md-fab md-fab-success', 'id' => 'saveButton', 'escape' => false]) ?>  
        </div>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-2-3 uk-width-large-3-4">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-form-row">
                            <div class="uk-grid">
                                <div class="uk-width-1-2">
                                    <label><?= __('Гарчиг') ?></label>
                                    <?= $this->Form->input('title', ['class' => 'md-input', 'label' => false]); ?>
                                </div>
                                <div class="uk-width-1-2">
                                    <label><?= __('Гарчиг') ?> (EN)</label>
                                    <?= $this->Form->input('title_en', ['class' => 'md-input', 'label' => false]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <div class="uk-grid">
                                <div class="uk-width-1-2">
                                    <label><?= __('Контент') ?></label>
                                    <?= $this->Form->input('subtitle', ['class' => 'md-input', 'label' => false, 'rows' => 2]); ?>
                                </div>
                                <div class="uk-width-1-2">
                                    <label><?= __('Контент') ?> (EN)</label>
                                    <?= $this->Form->input('subtitle_en', ['class' => 'md-input', 'label' => false, 'rows' => 2]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Линк') ?></label>
                            <?= $this->Form->input('link', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <div class="uk-grid">
                                <div class="uk-width-medium-1-3">
                                    <label><?= __('Линк текст') ?></label>
                                    <?= $this->Form->input('linktext', ['class' => 'md-input', 'label' => false]); ?>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <label><?= __('Линк текст') ?> (EN)</label>
                                    <?= $this->Form->input('linktext_en', ['class' => 'md-input', 'label' => false]); ?>
                                </div>
                                <div class="uk-width-medium-1-3" style="padding-top:10px">
                                    <?= $this->Form->checkbox('link_newtab', ['data-switchery' => '', 'id' => 'link_newtab', 'class' => 'uk-margin']) ?>
                                    <label for="link_newtab" class="inline-label"><?= __('Шинэ цонх нээнэ') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <?= $this->Form->hidden('media_type', ['value' => 'image']); ?>
                            <?= $this->element("filemanager/image_load_js", ['model' => $banner, 'image' => 'media']) ?>
                        </div>
                        <?php  /*
                        <div class="uk-form-row call_to_action">
                              <div class="uk-grid" uk-grid-margin>
                                <div class="uk-width-1-2"><label>Call to action</label></div>
                                <div class="uk-width-1-2"><label>Call to action (EN)</label></div>
                                  <div class="uk-width-1-2">
                                      <?= $this->Form->input('call_to_action', ['class' => 'md-input', 'label' => false, 'rows' => 2]); ?>
                                  </div>
                                  <div class="uk-width-1-2">
                                      <?= $this->Form->input('call_to_action_en', ['class' => 'md-input', 'label' => false, 'rows' => 2]); ?>
                                  </div>
                              </div>
                          <a href="#icons" data-uk-modal class="uk-button uk-margin-top uk-button-small">Боломжит icon уудийг харах <i class="uk-icon-question-circle"></i></a>
                          </div>*/ ?>
                    </div>
                </div>
            </div>
            <div class="uk-width-medium-1-3 uk-width-large-1-4">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-form-row">                            
                            <?= $this->Form->checkbox('active', ['data-switchery' => '', 'id' => 'active_check']) ?>
                            <label for="active_check" class="inline-label"><?= __('Идэвхтэй') ?></label>
                        </div>
                        <div class="uk-form-row">                            
                            <?= $this->Form->checkbox('never_end', ['data-switchery' => '', 'id' => 'never_end']) ?>
                            <label for="never_end" class="inline-label"><?= __('Хугацаагүй харагдна') ?></label>
                        </div>
                        <div class="uk-form-row" id="startdate_wrapper">                            
                            <label><?= __('Эхлэх огноо') ?></label>
                            <?= $this->Form->input('startdate', ['label' => false, 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY.MM.DD'}", 'class' => 'md-input', 'value' => !empty($banner->startdate) ? $banner->startdate->format('Y.m.d') : '']) ?>
                        </div>
                        <div class="uk-form-row"  id="enddate_wrapper">
                            <label><?= __('Дуусах огноо') ?></label>
                            <?= $this->Form->input('enddate', ['label' => false, 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY.MM.DD'}", 'class' => 'md-input', 'value' => !empty($banner->enddate) ? $banner->enddate->format('Y.m.d') : '']) ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Хэл') ?></label>
                            <?= $this->Form->input('language', ['class' => 'md-input', 'label' => false, 'options' => $banner->languages]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Дараалал') ?></label>
                            <?= $this->Form->input('position', ['class' => 'md-input', 'label' => false]); ?>
                        </div>
                        <div class="uk-form-row">
                            <label><?= __('Төрөл') ?></label>
                            <?= $this->Form->input('type', ['class' => 'md-input', 'label' => false, 'options' => $banner->types]); ?>
                        </div>
                      <?php /*
                        <div class="uk-form-row rw-popup-option" id="homepage_visible">                            
                            <?= $this->Form->checkbox('homepage', ['data-switchery' => '', 'id' => 'homepage_check']) ?>
                            <label for="homepage_check" class="inline-label"><?= __('Нүүр хуудсанд харагдна.') ?></label>
                        </div>*/ ?>
                      <div class="uk-form-row rw-popup-option">
                            <label><?= __('Давтамж') ?> (цаг)  <i class="uk-icon-question-circle"></i></label>
                            <?= $this->Form->input('frequency', ['class' => 'md-input', 'label' => false, 'title' => 'Энэ хугацаанд баннер дахин харагдахгүй. Сайтад орох бүрт харуулах бол 0 гэж оруулна.', 'data-uk-tooltip' => "{cls:'long-text', pos:'bottom'}"]); ?>
                        </div>
                      <div class="uk-form-row rw-popup-option">
                            <label><?= __('Хүлээх хугацаа') ?> (cекунд)  <i class="uk-icon-question-circle"></i></label>
                            <?= $this->Form->input('delay', ['class' => 'md-input', 'label' => false, 'title' => 'Сайт ачааллаж дууссанаас хойш энэ хугацааны дараа харагдна.', 'data-uk-tooltip' => "{cls:'long-text', pos:'bottom'}"]); ?>
                        </div>
                      <?php /*
                      <div class="uk-form-row rw-popup-option">                            
                            <?= $this->Form->checkbox('after_scroll', ['data-switchery' => '', 'id' => 'after_scroll']) ?>
                            <label for="after_scroll" class="inline-label"><?= __('Доош гүйлгэсний дараа') ?> <i class="uk-icon-question-circle" data-uk-tooltip="{cls:'long-text'}" title="<?= __("Дээрх хугацааг scroll хийсний дараагаар тооцож эхэлнэ.") ?>"></i></label>
                        </div>
                        <div class="uk-form-row rw-popup-option">  
                            <?= $this->Form->checkbox('block_ui', ['data-switchery' => '', 'id' => 'block_ui']) ?>
                          <label for="block_ui" class="inline-label"><?= __('Дэлгэцийг түгжинэ') ?> <i class="uk-icon-question-circle" data-uk-tooltip="{cls:'long-text'}" title="<?= __("Дэлгэцийн хаа нэгтээ дарж баннерыг хаах боломжгүй болгоно. Зөвхөн баннер дээрх хаах товч дээр дарж хаана") ?>"></i></label>
                        </div>
                      */?>
                      
                      
                        <?= $this->element("locations/show_on_pages", ['model' => $banner]) ?>
                        <?= $this->element("locations/show_on_products", ['model' => $banner]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php //echo $this->element('site/icons_modal') ?>

<?= $this->element("cms/editor") ?>

<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    var btns = ['bold', 'italic', 'underline', 'fontFamily', 'fontSize', '|', 'color', 'specialCharacters', 'emoticons', 'paragraphStyle', 'inlineStyle', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'insertHR', '|', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting', '|', 'html', 'fullscreen'];
    var btns2 = ['bold', 'italic', 'underline', 'fontSize', '|', 'color', 'specialCharacters', 'emoticons', 'paragraphStyle', 'inlineStyle', 'paragraphFormat', '-', 'insertLink', 'insertTable', 'undo', 'redo', 'clearFormatting', 'html'];
    var options = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>', btns);
    var options2 = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>', btns2);

    function initEditor(selector, editorOption){
        REWRITE.init([null, editorOption, false, selector]);
        REWRITE.bindEditor();
    }
    
    $(function () {
        initEditor('textarea#subtitle', options);
        initEditor('textarea#subtitle-en', options);
        
        initEditor('textarea#call-to-action', options2);
        initEditor('textarea#call-to-action-en', options2);
        
        // Toggle start and end dates when never end checkbox changes
        $('#never_end').on('change', function() {
            if(this.checked) {
                $("#startdate_wrapper").hide();
                $("#enddate_wrapper").hide();
            } else {
                $("#startdate_wrapper").show();
                $("#enddate_wrapper").show();
            }
        });
        
        $( "#never_end" ).trigger( "change" );
        
        $('#type').on('change', function() {
//            if($(this).val() == "block") {
//                $("#homepage_visible").show();
//            } else {
//                $("#homepage_visible").hide();
//            }
            
            if($(this).val() == "homecover") {
                $(".call_to_action").show();
            } else {
                $(".call_to_action").hide();
            }
            
            if($(this).val() == "popup" || $(this).val() == "popup_footer") {
                $(".rw-popup-option").show();
            } else {
                $(".rw-popup-option").hide();
            }
            
            
        });
        $( "#type" ).trigger( "change" );
    });
    

    
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>