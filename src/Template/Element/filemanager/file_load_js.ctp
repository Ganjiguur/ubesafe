<?php if (isset($model)): ?>
    <?php
    $maxSize = isset($maxSize) ? $maxSize : 30*1024; //(KB)
    $field = isset($field) ? $field : 'file'; //(KB)
    echo $this->Html->css(getUikitCssPath('components/form-file.almost-flat.min'), ['block' => true]);

    if (isset($model[$field]) && $model[$field] != "") {
        $hasFile = true;
    } else {
        $hasFile = false;
    }
    ?>
    <div class="uk-form-file md-btn md-btn-primary md-btn-block md-btn-wave-light md-btn-icon" <?= $hasFile ? 'style="display:none"' : '' ?> id="addFile">
        <i class="uk-icon-file-o"></i> 
        <?= __("Файл оруулах") ?>
        <?= $this->Form->input('upload_file', ['type' => 'file', 'label' => false, 'id' => 'upload_file']); ?>
    </div> 

    <button id="deleteFileButton" type="button" class="md-btn md-btn-danger md-btn-block md-btn-wave-light md-btn-icon" <?= $hasFile ? '' : 'style="display:none"' ?>>
        <i class="uk-icon-times"></i>
        <?= __("Файл устгах") ?>
    </button>
    <div id='errorSize' class="uk-text-danger uk-margin error-msg" hidden>
        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
        <?= __("Файлын хэмжээ {0} KB аас бага байх ёстой", $maxSize) ?>
    </div>
    <div id='errorFile' class="uk-text-danger uk-margin error-msg" hidden>
        <i class="uk-icon uk-icon-exclamation-triangle"></i> 
        <?= __("Сонгосон файл буруу байна!") ?>
    </div>
    <div id='filePreview' class="uk-form-row uk-margin-small-top">
        <?= $model[$field] ?>
    </div>
    <?= $this->Form->hidden('delete_file', ['value' => 'false']); ?>
    <?php $this->Form->unlockField('delete_file'); ?>


  <?php if (false) : ?>
    <script>
  <?php else: ?>
    <?php $this->Html->scriptStart(['block' => true]); ?>
  <?php endif; ?>
        $(function () {
            $('#deleteFileButton').click(function () {
                $('input[name=delete_file]').val(true);
                $('#deleteFileButton').hide();
                $('#addFile').show();
                $('#filePreview').empty();
            });

            var fileInput = $('#upload_file');
            var fileDisplayArea = $('#filePreview');
            var maxSize = <?= $maxSize ?>;
            var validFIle = false;
            
            function toggleError(elId, show) {
                if (show) {
                    $("#"+elId).show();
                    validFIle = false;
                } else {
                    $("#"+elId).hide();
                    
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
                validFIle = true;
                $(".error-msg").hide();
//                var fileType = /image.*/;
                //validate file type
//                toggleError("errorFile", !file.type.match(fileType));
                //validate image size
                //1MB = 1 byte * 1024 * 1024
                toggleError("errorSize", file.size > 1024 * maxSize);
                // display image
                if (validFIle) {
                    fileDisplayArea.html(file.name);
                } else {
                    toggleBtns(validFIle);
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