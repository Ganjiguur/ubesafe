<?php if (isset($model)): ?>
  <?php
  echo $this->Html->css(getUikitCssPath('components/form-file.almost-flat.min'), ['block' => true]);

  $minWidth = isset($minWidth) ? $minWidth : 5; //(PX)
  $minHeight = isset($minHeight) ? $minHeight : 5; //(PX)
  $maxSize = isset($maxSize) ? $maxSize : 5000; //(KB)
  $image = isset($image) ? $image : 'image'; //(KB)


  if (!empty($model[$image])) {
    $hasImage = true;
  } else {
    $hasImage = false;
  }
  ?>
  <div class="uk-form-file md-btn md-btn-primary md-btn-block md-btn-wave-light md-btn-icon" <?= $hasImage ? 'style="display:none"' : '' ?> id="addImage">
    <i class="uk-icon-file-image-o"></i> 
    <?= __("Зураг оруулах") ?>
    <?= $this->Form->input('image_file', ['type' => 'file', 'label' => false]); ?>
  </div> 
  <button id="deleteImageButton" type="button" class="md-btn md-btn-danger md-btn-block md-btn-wave-light md-btn-icon" <?= $hasImage ? '' : 'style="display:none"' ?>>
    <i class="uk-icon-times"></i>
    <?= __("Зураг устгах") ?>
  </button>
  <div id='errorTooBig' class="uk-text-danger uk-margin error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Image is it too big. Please resize it down first (must be less than 3300x3300)") ?>
  </div>
  <div id='errorSize' class="uk-text-danger uk-margin error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Зургийн хэмжээ {0} KB аас бага байх ёстой", $maxSize) ?>
  </div>
  <div id='errorWidth' class="uk-text-danger uk-margin error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Зургийн өргөн {0} аас багагүй байх ёстой", $minWidth) ?>
  </div>
  <div id='errorHeight' class="uk-text-danger uk-margin error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Зургийн өндөр {0} аас багагүй байх ёстой", $minHeight) ?>
  </div>
  <div id='errorFile' class="uk-text-danger uk-margin error-msg" hidden>
    <i class="uk-icon uk-icon-exclamation-triangle"></i> 
    <?= __("Сонгосон файл зураг биш байна!") ?>
  </div>
  <div id='imagePreview' class="uk-form-row uk-margin-small-top">
    <?php if ($hasImage): ?>
      <image src="<?= $model[$image] ?>">
    <?php endif; ?>
  </div>
  <?= $this->Form->hidden('delete_image', ['value' => 'false']); ?>
  <?php $this->Form->unlockField('delete_image'); ?>


  <?php if (false) : ?>
    <script>
  <?php else: ?>
    <?php $this->Html->scriptStart(['block' => true]); ?>
  <?php endif; ?>
    $(function () {
      $('#deleteImageButton').click(function () {
        $('input[name=delete_image]').val(true);
        $('#deleteImageButton').hide();
        $('#addImage').show();
        $('#imagePreview').empty();
      });

      var fileInput = $('#image-file');
      var fileDisplayArea = $('#imagePreview');
      var minWidth = <?= $minWidth ?>;
      var minHeight = <?= $minHeight ?>;
      var maxSize = <?= $maxSize ?>;
      var validImage = false;

      function toggleError(elId, show) {
        if (show) {
          $("#" + elId).show();
          validImage = false;
        } else {
          $("#" + elId).hide();

        }
      }

      function toggleBtns(enable) {
        $('#saveButton').prop('disabled', !enable);
        $('button[type=submit]').prop('disabled', !enable);
      }

      fileInput.change(function (e) {
        if (!fileInput[0].files.length > 0)
          return;

        var file = fileInput[0].files[0];
        // file shaardlaga hangaj baigaag shalgaj baigaa huvisagch
        validImage = true;
        $(".error-msg").hide();
        var imageType = /image.*/;
        //validate file type
        toggleError("errorFile", !file.type.match(imageType));
        //validate image size
        //1MB = 1 byte * 1024 * 1024
        toggleError("errorSize", file.size > 1024 * maxSize);

        // display image
        if (validImage) {
          var fileReader = new FileReader();
          fileReader.onload = function (e) {
            fileDisplayArea.html('');
            var img = new Image();

            img.onload = function () {
              //preventing server memory error
              toggleError("errorTooBig", this.naturalWidth * this.naturalHeight > 3300 * 3300);
              //validating image width
              toggleError("errorWidth", this.naturalWidth < minWidth);
              //validating image height
              toggleError("errorHeight", this.naturalWidth < minHeight);

              toggleBtns(validImage);
            };
            img.src = fileReader.result;
            fileDisplayArea.html(img);
          };
          fileReader.readAsDataURL(file);
        } else {
          toggleBtns(validImage);
          fileDisplayArea.html('');
        }
      });

    });

  <?php if (false) : ?>
    </script>
  <?php else: ?>
    <?php $this->Html->scriptEnd(); ?>
  <?php endif; ?>

<?php else: ?> 
  <p>Not enough information</p>
<?php endif; ?>