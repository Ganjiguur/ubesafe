<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$isAdmin = $this->AppUser->isAdmin();

$hasCover = (isset($article->cover_image) && $article->cover_image != "");

$this->Form->unlockField('selectize_lang');
$slugify = $article->published ? [] : ['data-slugify'=>'slug'];
?>

<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-2-3 uk-width-large-3-4">
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-3-4">
                            <label><?= __('Гарчиг') ?></label>    
                            <?= $this->Form->input('title', ['class' => 'md-input', 'label' => false, 'required' => true] + $slugify); ?>
                        </div>
                        <div class="uk-width-1-4">
                            <?= $this->Form->input('language', ['id' => 'article_lang', 'required' => true, 'label' => false, 'options' => $article->languages]); ?>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <label><?= __('Slug') ?></label>    
                    <?= $this->Form->input('slug', ['class' => 'md-input', 'label' => false, 'required' => true, 'data-show-change' => 'slug_val']); ?>
                    <span class="uk-form-help-block">
                        <?= __("The 'slug' is the URL-friendly version of the title. It must be all lowercase and contains only letters, numbers, and hyphens(-).") ?>
                        <br>
                        <strong><?= __("Permalink") ?>: </strong>
                        <?= $this->Url->build(['controller'=>'Articles','action'=>'view'],true) ?>/<strong id="slug_val"><?= $article->slug ?></strong>
                    </span>
                </div>
                <div class="uk-form-row">
                    <label><?= __('Тайлбар') ?></label>    
                    <?= $this->Form->input('sub_title', ['class' => 'md-input', 'label' => false, 'rows' => 1]); ?>
                </div>
                <div class="uk-form-row">
                    <?php echo $this->Form->input('html', ['class' => 'uk-width-1-1', 'rows' => 7, 'id' => "rewrite", 'label' => false]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-medium-1-3 uk-width-large-1-4">
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-width-medium-1-2">
                            <label for="published_check" class="inline-label"><?= __('Нийтлэгдсэн') ?></label>
                            <?= $this->Form->checkbox('published', ['data-switchery' => '', 'id' => 'published_check']) ?>
                        </div>
                        <div class="uk-width-1-1 uk-width-medium-1-2">
                            <label for="featured_check" class="inline-label"><?= __('Онцлох') ?></label>
                            <?= $this->Form->checkbox('featured', ['data-switchery' => '', 'id' => 'featured_check']) ?>
                    </div>
                </div>
                <div class="uk-form-row md-input-wrapper label-fixed md-input-filled">
                    <label class=""><?= __('Категори') ?></label>
                    <input type="hidden" name="categories[_ids]" value="">
                    <div class="uk-scrollable-box uk-margin-top" style="height:300px">
                        <?php 
                            $spacer = "->"; 
                            $articleCategories = [];
                            foreach ($article->categories as $cat) {
                                $articleCategories[$cat->id] = true;
                            }
                        ?>
                        <?php foreach ($categories as $id => $cname) : ?>
                            <?php $_name = str_replace($spacer, "", $cname, $count); ?>
                            <div class="checkbox uk-margin-small" style="margin-left: <?= $count * 20 ?>px">
                                <?= $this->Form->checkbox('categories[_ids][]', ['value' => $id, 'hiddenField' => false, 'data-md-icheck' => '', 'id' => 'categories-ids-' . $id, 'checked'=>isset($articleCategories[$id])]); ?>
                                <label for="categories-ids-<?= $id ?>" class="inline-label"><?= $_name ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-form-file md-btn md-btn-primary md-btn-block md-btn-wave-light md-btn-icon" <?= $hasCover ? 'style="display:none"' : '' ?> id="addCover">
                        <i class="uk-icon-file-image-o"></i>
                        <?= __("Ковер зураг оруулах") ?>
                        <?= $this->Form->input('cover_image_file', ['type' => 'file', 'label' => false]); ?>
                    </div> 
                    <button id="deleteButton" type="button" class="md-btn md-btn-danger md-btn-block md-btn-wave-light md-btn-icon" <?= $hasCover ? '' : 'style="display:none"' ?>>
                        <i class="uk-icon-times"></i>
                        <?= __("Зураг устгах") ?>
                    </button>

                    <div id='warningTooBig' class="uk-text-danger uk-margin" hidden>
                        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                        <?= __("Image is it too big. Please resize it down first (must be less than 3300x3300)") ?>
                    </div>
                    <div id='warningSize' class="uk-text-danger uk-margin" hidden>
                        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                        <?= __("Зургийн хэмжээ 5MB аас бага байх ёстой") ?>
                    </div>
                    <div id='warningDimension' class="uk-text-danger uk-margin" hidden>
                        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                        <?= __("Мэдээний ковер зурагны өргөн 360 аас багагүй, өндөр 240 аас багагүй байх ёстой") ?>
                    </div>
                    <div id='warningFile' class="uk-text-danger uk-margin" hidden>
                        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
                        <?= __("Сонгосон файл зураг биш байна!") ?>
                    </div>
                    <?= $this->Form->hidden('delete_cover_image', ['value' => 'false']); ?>
                    <?php $this->Form->unlockField('delete_cover_image'); ?>

                    <?= $this->Form->hidden('x_cover', ['id' => 'x-cover']) ?>
                    <?= $this->Form->hidden('y_cover', ['id' => 'y-cover']) ?>
                    <?= $this->Form->hidden('width_cover', ['id' => 'width-cover']) ?>
                    <?= $this->Form->hidden('height_cover', ['id' => 'height-cover']) ?>
                    <?= $this->Form->hidden('ratio_cover', ['id' => 'ratio-cover']) ?>
                    <?= $this->Form->hidden('x_square', ['id' => 'x-square']) ?>
                    <?= $this->Form->hidden('y_square', ['id' => 'y-square']) ?>
                    <?= $this->Form->hidden('width_square', ['id' => 'width-square']) ?>
                    <?= $this->Form->hidden('height_square', ['id' => 'height-square']) ?>
                    <?= $this->Form->hidden('ratio_square', ['id' => 'ratio-square']) ?>
                    <?php $this->Form->unlockField('x_cover'); ?>
                    <?php $this->Form->unlockField('y_cover'); ?> 
                    <?php $this->Form->unlockField('width_cover'); ?>
                    <?php $this->Form->unlockField('height_cover'); ?>
                    <?php $this->Form->unlockField('ratio_cover'); ?>
                    <?php $this->Form->unlockField('x_square'); ?>
                    <?php $this->Form->unlockField('y_square'); ?> 
                    <?php $this->Form->unlockField('width_square'); ?>
                    <?php $this->Form->unlockField('height_square'); ?>
                    <?php $this->Form->unlockField('ratio_square'); ?>
                </div>
                <div id='imagePreview' class="uk-form-row uk-margin-small-top">
                    <img src="<?= empty($article->cover_image) ? "" : $article->cover_image ?>" id="cover-img" style="max-height: 240px;">
                </div>
                <p id="cropper-text" class="uk-text-danger" hidden="hidden"><?= __("Мэдээний лист-д хуудсанд харагдах хэсгийг сонгоно уу") ?></p>
                <div id='imagePreviewSquare' class="uk-form-row uk-margin-small-top">
                    <img src="<?= empty($article->cover_image) ? "" : $article->square_image ?>" id="square-img" style="max-height: 240px;">
                </div>

            </div>
        </div>
        <div class="md-card">
          <div class="md-card-content">
            <h4>Бусад тохиргоо</h4>
            <?php /*
            <div class="uk-form-row">
              <label for="hide_on_mobile" class="inline-label"><?= __('Гар утсан дээр харуулахгүй') ?></label>
              <?= $this->Form->checkbox('hide_on_mobile', ['data-switchery' => '', 'id' => 'hide_on_mobile']) ?>
            </div>*/ ?>
            <div class="uk-form-row">
                <label class=""><?= __('Үүсгэсэн огноо') ?></label>
                <div class="uk-grid md-input label-fixed">
                    <div class="uk-width-1-2">
                        <?= $this->Form->input('created_date', ['label' => false, 'type' => 'text', 'data-uk-datepicker' => "{format:'YYYY.MM.DD'}", 'class' => 'md-input', 'value' => $article->created ? $article->created->format('Y.m.d') : '']) ?>
                    </div>
                    <div class="uk-width-1-2">
                        <?= $this->Form->input('created_time', ['label' => false, 'type' => 'text', 'data-uk-timepicker' => "", 'class' => 'md-input', 'value' => $article->created ? $article->created->format('H:i') : '', 'autocomplete' => 'off']) ?>
                    </div>
                </div>
            </div>
            <?= $this->element('cms/revision_count', ['entity' => $article, '_name' => 'articles']) ?>
          </div>
        </div>
    </div>
</div>