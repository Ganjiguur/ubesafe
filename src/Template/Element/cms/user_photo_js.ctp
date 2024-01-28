<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    $(function () {
        var base_url = '<?= $this->AppView->getFullBaseUrl() ?>/img/';

        var fileInput = document.getElementById('user-photo');
        var fileDisplayArea = document.getElementById('imagePreview');
        var imageId = "photo";

        function initCropper(size) {
            var previewRatio = $('#'+imageId).width() / size.width;
            var smallest = size.width >= size.height ? size.height : size.width;
            smallest = smallest * previewRatio;
            document.getElementById('preview-ratio').value = previewRatio;
            var cropper = new Cropper(document.getElementById(imageId), {
                ratio: {width: 1, height: 1},
                min_width: 300 * previewRatio,
                min_height: 300 * previewRatio,
                max_width: smallest,
                max_height: smallest,
                update: function (coordinates) {
                    for (var i in coordinates) {
                        document.getElementById(i + '-photo').value = coordinates[i];
                    }
                }
            });
            var coords = {
                x: 0,
                y: 0,
                width: smallest,
                height: smallest
            };
            cropper.draw(coords);
        }

        fileInput.addEventListener('change', function (e) {
            var file = fileInput.files[0];
            // file shaardlaga hangaj baigaag shalgaj baigaa huvisagch
            var validImage = true;
            var imageType = /image.*/;
            if (file.type.match(imageType)) {
                //validate image size 
                //1MB = 1 byte * 1024 * 1024
                if (file.size > 1024 * 1024 * 3) {
                    $("#warningSize").show();
                    validImage = true;
                } else {
                    $("#warningSize").hide();
                }

                // display image
                var fileReader = new FileReader();
                fileReader.onload = function (e) {
                    fileDisplayArea.innerHTML = "";
                    var img = new Image(250);
                    img.id = 'photo';
                    //validating image height width max = 1024x1024 
                    img.onload = function () {
                        if (this.naturalWidth * this.naturalHeight > 3300 * 3300) {
                            $("#warningTooBig").show();
                            validImage = false;
                        } else {
                            $("#warningTooBig").hide();
                        }
                        
                        if (this.naturalWidth >= 300 && this.naturalHeight >= 300) {
                            $("#warningHeight").hide();
                            var imgSize = {
                                width: this.naturalWidth,
                                height: this.naturalHeight
                                
                            };

                            initCropper(imgSize);
                        } else {
                            $("#warningHeight").show();
                            validImage = false;
                        }
                    };
                    img.src = fileReader.result;
                    fileDisplayArea.appendChild(img);
                };
                fileReader.readAsDataURL(file);
                if (validImage) {
                    $('#saveButton').prop('disabled', false);
                } else {
                    $('#saveButton').prop('disabled', true);
                }
            } else {
                fileDisplayArea.innerHTML = "<?= __("Chosen file isn't an image!") ?>";
            }
        });

        $('#deleteButton').click(function () {
            $('input[name=delete_photo]').val(true);
            $('#deleteButton').hide();
            $('#addPhoto').show();
            $('#imagePreview').empty();
        });

    });
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>