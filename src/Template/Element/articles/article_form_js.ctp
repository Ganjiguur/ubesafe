<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  $(function () {
        var articleId = "<?= $article->id ?>";
        var published = '<?= $article->published ?>';
        var autoSaveInterval = 60 * 1000; 
        var autoSaveFields = ['body'];
        var autoSaveData = [];
        
        var inlineEditor = <?= (isset($inlineEditor) && $inlineEditor) ? 'true' : 'false' ?>;
        
        var fileInput = $('#cover-image-file');
        var fileDisplayAreaId = 'imagePreview';
        var validateImageSize = true;
        var validateImageDimenstion = true;
        var imageId = 'cover-img';
        var imageSquareId = 'square-img';

        var options = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>');
        options['heightMin'] = 400;
        options['placeholderText'] = "Article html content";

        if(inlineEditor) {
            options.toolbarInline = true;
            options.pluginsEnabled = null;
            options.toolbarVisibleWithoutSelection = false;
            options.quickInsertButtons = ['image', 'insertImage','insertVideo','table', 'ul', 'ol', 'hr'];
            options.quickInsertTags = ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'blockquote'];
            options.pluginsEnabled= null;
            options.placeholderText = "<?= __("Та нийтлэлээ энд дараад бичиж эхэлнэ үү") ?>";
        }

        var selector = 'textarea#rewrite';

        REWRITE.init([null, options, false, selector]);
        REWRITE.bindEditor();
        
        $(selector).on('froalaEditor.video.inserted', function (e, editor, $video) {
          $video.find('iframe').attr('width','715');
          $video.find('iframe').attr('height','402');
       });
       
        function getFormData() {
            var data = []; 
            data['body'] = $(selector).froalaEditor('html.get');
            return data;
        }
        
        function autoSave() {
            if (!$(selector).data('froala.editor')) { return; }
            
            var autoSaveDataTemp = getFormData();

            var _data = {};
            for (var i = 0; i < autoSaveFields.length; i++) {
                var _field = autoSaveFields[i];
                 if (autoSaveData[_field] != autoSaveDataTemp[_field]) {
                    _data[_field] = autoSaveDataTemp[_field];
                }
            }

            if (!$.isEmptyObject(_data)) {
                $('.autosave_status').show();
                _data['article_id'] = articleId;
                $.ajax({
                    url: "<?= $this->Url->build(["controller" => "Articles", "action" => "ajaxSaveArticle"]) ?>",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');
                    },
                    data: _data
                }).done(function (data) {
                    $(".autosave_error").hide();
                    $(".autosave_success").show();
                    for (var i = 0; i < autoSaveFields.length; i++) {
                        var _field = autoSaveFields[i];   
                        if(_data[_field]) {
                            autoSaveData[_field] = _data[_field];
                        }
                    }
                }).fail(function (data) {
                    $(".autosave_success").hide();
                    $(".autosave_error").show();
                    console.log(data.responseText);
                }).always(function() {
                    $('.autosave_status').hide();
                });
            }
        }

        if (published !== '1') {
            autoSaveData = getFormData();
            setInterval(autoSave, autoSaveInterval);
        }

        $('#deleteButton').click(function () {
            $('input[name=delete_cover_image]').val(true);
            $('#deleteButton').hide();
            $('#addCover').show();
            $('#'+fileDisplayAreaId).empty();
            $('#'+fileDisplayAreaId + 'Square').empty();
        });

        fileInput.change(function (e) {
            if (!fileInput[0].files.length > 0)
                return;
            
            $('#addCover').removeClass("uk-placeholder-large");

            var file = fileInput[0].files[0];
            // file shaardlaga hangaj baigaag shalgaj baigaa huvisagch
            var validImage = true;
            var imageType = /image.*/;
            if (file.type.match(imageType)) {
                //validate image size 
                //1MB = 1 byte * 1024 * 1024
                if (validateImageSize && file.size > 1024 * 1024 * 5) {
                    $("#warningSize").show();
                    validImage = false;
                } else {
                    $("#warningSize").hide();
                }
                // display image
                var fileReader = new FileReader();
                fileReader.onload = function (e) {
                    $('#'+fileDisplayAreaId).innerHTML = "";
                $('#'+fileDisplayAreaId + 'Square').innerHTML = "";
                
                    var img = new Image();
                    
                    //validating image width and height
                    img.onload = function () {
                        if (this.naturalWidth * this.naturalHeight > 3300 * 3300) {
                            $("#warningTooBig").show();
                            validImage = false;
                        } else {
                            $("#warningTooBig").hide();
                        }
                        
                        if (validateImageDimenstion && ( this.naturalWidth < 360 || this.naturalHeight < 240)) {
                            $("#warningDimension").show();
                            validImage = false;
                        } else {
                            $("#warningDimension").hide();
                            
                            var imgSize = {
                                width: this.naturalWidth,
                                height: this.naturalHeight
                            };

                            initCropper(imgSize, 'cover', 360, 200);
                            initCropper(imgSize, 'square', 360, 200, true);
                        }
                        if (validImage) {
                        $('#saveButton').prop('disabled', false);
                        } else {
                            $('#saveButton').prop('disabled', true);
                        }
                    };
                    img.src = fileReader.result;
                    img.id = imageId;
                    $(img).css("max-height","240px");
                    $('#'+fileDisplayAreaId).html(img);
                    var imgSquare = new Image();
                    imgSquare.src = fileReader.result;
                    imgSquare.id = imageSquareId;
                    $(imgSquare).css("max-height","240px");
                    $('#'+fileDisplayAreaId + 'Square').html(imgSquare);
                };
                fileReader.readAsDataURL(file);
            } else {
                $('#'+fileDisplayAreaId).empty();
                $('#'+fileDisplayAreaId + 'Square').empty();
                $("#warningFile").show();
            }
        });
        
        function initCropper(size, type, min_width, min_height, ratio = false) {
            var container = $('#'+type + '-img');
            var previewRatio = container.width() / size.width;
            
            document.getElementById('ratio-'+ type).value = previewRatio;
            var max_width, max_height, ratio;
            
            if (ratio) {
                ratio = {width: min_width, height: min_height};
                var tmpHeight = (size.width / min_width) * min_height;
                if(tmpHeight < size.height) {
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
            
            

            
            
            var cropper = new Cropper(document.getElementById(type + '-img'), {
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
            
            $("#cropper-text").show();
        }

        //init lang selector
        var $article_lang = $('#article_lang');
        if($article_lang.length) {
            $article_lang.selectize({
                render: {
                    option: function(data, escape) {
                        var value = data.value =='en'?'gb':data.value;
                        return  '<div class="option">' +
                            '<i class="item-icon flag-' + escape(value).toUpperCase() + '"></i> ' +
                            '<span>' + escape(data.title) + '</span>' +
                            '</div>';
                    },
                    item: function(data, escape) {
                        var value = data.value =='en'?'gb':data.value;
                        return  '<div class="item">' +
                            '<i class="item-icon flag-' + escape(value).toUpperCase() + '"></i> ' +
                            '<span>' + escape(data.title) + '</span>' +
                            '</div>';
                    }
                },
                valueField: 'value',
                labelField: 'title',
                searchField: 'title',
                create: false,
                hideSelected: false,
                onDropdownOpen: function($dropdown) {
                    $dropdown
                        .hide()
                        .velocity('slideDown', {
                            begin: function() {
                                $dropdown.css({'margin-top':'-33px'})
                            },
                            duration: 200,
                            easing: easing_swiftOut
                        })
                },
                onDropdownClose: function($dropdown) {
                    $dropdown
                        .show()
                        .velocity('slideUp', {
                            complete: function() {
                                $dropdown.css({'margin-top':''})
                            },
                            duration: 200,
                            easing: easing_swiftOut
                        });
                }
            });

            $article_lang.next().children('.selectize-input').find('input').attr('readonly',true);
            $article_lang.next().children('.selectize-input').find('input').attr('name','selectize_lang');
        }
    });
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>