<?php if (isset($model)): ?>
  <?php
  echo $this->Html->css('/cms/css/cropper', ['block' => true]);
  echo $this->Html->css(getUikitCssPath('components/form-file.almost-flat.min'), ['block' => true]);

  $crop = isset($crop) ? $crop : true; //(PX)
  $minWidth = isset($minWidth) ? $minWidth : 1930; //(PX)
  $minHeight = isset($minHeight) ? $minHeight : 370; //(PX)
  $keepRatio = isset($keepRatio) ? $keepRatio : true;
  $maxWidth = isset($maxWidth) ? $maxWidth : 3300; //(PX)
  $maxHeight = isset($maxHeight) ? $maxHeight : 3300; //(PX)
  $maxSize = isset($maxSize) ? $maxSize : 2000; //(KB)
  $imageFieldName = isset($imageFieldName) ? $imageFieldName : 'cover'; //(KB)
  $addButtonCaption = isset($addButtonCaption) ? $addButtonCaption : __('Select cover image');
  $deleteButtonCaption = isset($deleteButtonCaption) ? $deleteButtonCaption : __('Delete cover image');

  $imageExists = !empty($model[$imageFieldName . '_image']);
  ?>

  <div class="uk-text-danger uk-margin warningTooBig error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Image is too big. Please resize it down (must be less than {0}x{1})", $maxWidth, $maxHeight) ?>
  </div>
  <div class="uk-text-danger uk-margin warningSize error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __('Зургийн хэмжээ {0} KB аас бага байх ёстой', $maxSize) ?>
  </div>
  <div class="uk-text-danger uk-margin warningDimension error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Зурагны өргөн {0} аас багагүй, өндөр {1} аас багагүй байх ёстой", $minWidth, $minHeight) ?>
  </div>
  <div class="uk-text-danger uk-margin warningFile error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Сонгосон файл зураг биш байна!") ?>
  </div>
  <div class="uk-margin-small-top <?= $imageFieldName ?>-image-preview">
    <?php if ($imageExists): ?>
      <?= $this->Html->image($model[$imageFieldName . '_image']) ?>
    <?php endif; ?>
  </div>
  <div class="uk-form-file uk-margin-top md-btn md-btn-primary md-btn-block md-btn-wave-light md-btn-icon select_image" <?= $imageExists ? 'style="display:none"' : '' ?>>
    <i class="uk-icon-file-image-o"></i> 
    <?= $addButtonCaption ?>
    <?= $this->Form->input($imageFieldName . '_file', ['data-group' => $imageFieldName . '_image', 'class' => $imageFieldName . '-image-file', 'type' => 'file', 'label' => false]); ?>
  </div> 
  <button type="button" data-group="<?= $imageFieldName ?>_image" class="uk-button uk-button-danger uk-margin-top delete_button" <?= $imageExists ? '' : 'style="display:none"' ?>><?= $deleteButtonCaption ?></button>
  
  
  <?php
  echo $this->Form->hidden('delete_' . $imageFieldName . '_image', ['value' => 'false']);
  $this->Form->unlockField('delete_' . $imageFieldName . '_image');
  
  if($crop) {
    echo $this->Form->hidden('x_' . $imageFieldName, ['id' => 'x-' . $imageFieldName]);
    echo $this->Form->hidden('y_' . $imageFieldName, ['id' => 'y-' . $imageFieldName]);
    echo $this->Form->hidden('width_' . $imageFieldName, ['id' => 'width-' . $imageFieldName]);
    echo $this->Form->hidden('height_' . $imageFieldName, ['id' => 'height-' . $imageFieldName]);
    echo $this->Form->hidden('ratio_' . $imageFieldName, ['id' => 'ratio-' . $imageFieldName]);
    $this->Form->unlockField('x_' . $imageFieldName);
    $this->Form->unlockField('y_' . $imageFieldName);
    $this->Form->unlockField('width_' . $imageFieldName);
    $this->Form->unlockField('height_' . $imageFieldName);
    $this->Form->unlockField('ratio_' . $imageFieldName); 
  }
  
  ?>


  <?php if ($this->AppView->getSiteMeta('debug')) : ?>
    <script>
  <?php else: ?>
    <?php $this->Html->scriptStart(['block' => true]); ?>
  <?php endif; ?>

    $(function () {
      var fileInput = $('.<?= $imageFieldName ?>-image-file');
      var fileDisplayArea = '<?= $imageFieldName ?>-image-preview';
      var validateImageSize = true;
      var validateImageDimenstion = true;
      var validImage = false;

      $('.delete_button').click(function () {
        var group = $(this).attr('data-group');
        $('input[name=delete_' + group + ']').val(true);
        $(this).hide();
        $('.' + group + ' .select_image').show();
        $('.' + group + ' .<?= $imageFieldName ?>-image-preview').empty();
      });

      function toggleError(selector, invalid) {
        if (invalid) {
          $(selector).show();
          validImage = false;
        } else {
          $(selector).hide();
        }
      }

      function toggleBtns(enable) {
        $('button[type=submit]').prop('disabled', !enable);
        if(enable) {
          $('button[type=submit]').addClass('md-fab-success');
        } else {
          $('button[type=submit]').removeClass('md-fab-success');
        }
        
      }

      fileInput.change(function (e) {
        if (!this.files.length > 0)
          return;

        var group = $(this).attr('data-group');

        var file = this.files[0];
        // file shaardlaga hangaj baigaag shalgaj baigaa huvisagch
        validImage = true;
        var imageType = /image.*/;
        $("." + group + " .error-msg").hide();
        //validate file type
        toggleError("." + group + " .warningFile", !file.type.match(imageType));
        //validate image size
        //1MB = 1 byte * 1024 * 1024
        toggleError("." + group + " .warningSize", validateImageSize && file.size > 1024 * <?= $maxSize ?>);

        if (validImage) {
          var fileReader = new FileReader();
          fileReader.onload = function (e) {
            $("." + group + " ." + fileDisplayArea).empty();
            var img = new Image();

            img.onload = function () {
              //preventing server memory error
              toggleError("." + group + " .warningTooBig", this.naturalWidth * this.naturalHeight > <?= $maxWidth ?> * <?= $maxHeight ?>);
              
              //validating image width
              toggleError("." + group + " .warningDimension", validateImageDimenstion && (this.naturalWidth < <?= $minWidth ?> || this.naturalWidth < <?= $minHeight ?>));
              
              toggleBtns(validImage);
              
              if (validImage && <?= $crop ? 1 : 0 ?>) {
                if (group == '<?= $imageFieldName ?>_image') {
                  var imgSize = {
                    width: this.naturalWidth,
                    height: this.naturalHeight
                  };
                  initCropper(imgSize, '<?= $imageFieldName ?>', <?= $minWidth ?>, <?= $minHeight ?>, <?= $keepRatio ? 1 : 0 ?>);
                }
              }
            };
            img.src = fileReader.result;
            $("." + group + " ." + fileDisplayArea).html(img);
          };
          fileReader.readAsDataURL(file);
        } else {
          toggleBtns(validImage);
          $("." + group + " ." + fileDisplayArea).empty();
        }
      });

      function initCropper(size, type, min_width, min_height, ratio = false) {
        var container = $('.' + type + '_image .<?= $imageFieldName ?>-image-preview');
        var previewRatio = container.width() / size.width;

        document.getElementById('ratio-' + type).value = previewRatio;
        var max_width, max_height, ratio;

        if (ratio) {
          ratio = {width: min_width, height: min_height};
          var tmpHeight = (size.width / min_width) * min_height;
          if (tmpHeight < size.height) {
            max_width = size.width * previewRatio;
            max_height = tmpHeight * previewRatio;
          } else {
            max_width = (size.height / min_height) * min_width * previewRatio;
            max_height = size.height * previewRatio;
          }
        } else {
          ratio = {};
          max_width = container.width();
          max_height = container.height();
        }

        var cropper = new Cropper(container.children('img')[0], {
          ratio: ratio,
          min_width: min_width * previewRatio,
          min_height: min_height * previewRatio,
          max_width: max_width,
          max_height: max_height,
          update: function (coordinates) {
            for (var i in coordinates) {
              document.getElementById(i + '-' + type).value = coordinates[i];
            }
          }
        });
        var coords = {
          x: 0,
          y: 0,
          width: max_width,
          height: max_height
        };
        cropper.draw(coords);
      }

    });

  <?php if ($this->AppView->getSiteMeta('debug')) : ?>
    </script>
  <?php else: ?>
    <?php $this->Html->scriptEnd(); ?>
  <?php endif; ?>

  <?= $this->Html->script('/cms/js/cropper.min', ['block' => true]); ?>
<?php endif; ?>