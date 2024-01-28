<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  
    $(function () {
        var host = window.location.hostname;
        var pageId = "<?= $page->id ?>";
        var published = '<?=$page->status ?>';
        var autoSaveInterval = 60 * 1000; 
        var autoSaveFields = ['body'];
        var autoSaveData = [];
        
        var options = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>');
        options['heightMin'] = 400;
        options['placeholderText'] = "Page html content";

        var selector = 'textarea#rewrite';

        REWRITE.init([null, options, false, selector]);
        REWRITE.bindEditor();
        
        function getFormData() {
            var data = []; 
            data['body'] = $(selector).froalaEditor('html.get');
            return data;
        }
        
        function autoSave() {
            if(!$(selector).data('froala.editor')) { return; }
            
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
                _data['page_id'] = pageId;
                $.ajax({
                    url: "<?= $this->Url->build(["controller"=>"Pages", "action"=>"ajaxSavePage"]) ?>",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');},
                    data: _data
                }).done(function (data) {
                    $(".autosave_error").hide();
                    $(".autosave_success").fadeIn('slow').delay(2000).fadeOut('slow');
                    for (var i = 0; i < autoSaveFields.length; i++) {
                        var _field = autoSaveFields[i];   
                        if(_data[_field]) {
                            autoSaveData[_field] = _data[_field];
                        }
                    }
                }).fail(function (data) {
                    $(".autosave_success").hide();
                    $(".autosave_error").show();
                    console.log(data);
                }).always(function() {
                    $('.autosave_status').hide();
                });
            }
        }
        
        if(published !== '1') {
            autoSaveData = getFormData();
            setInterval(autoSave, autoSaveInterval);
        }
        
        //init lang selector
        var $page_lang = $('#page_lang');
        if($page_lang.length) {
            $page_lang.selectize({
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

            $page_lang.next().children('.selectize-input').find('input').attr('readonly',true);
            $page_lang.next().children('.selectize-input').find('input').attr('name','selectize_lang');
        }

    });
    
    
    /**** FOR PAGES TABBED CONTENT ****/
    var tabs_mn = <?= count($page->tabs ?: [[]]) ?>;
    
    $(function () {
        for (var i = 0; i < tabs_mn; i++) {
            initEditor('.tabs_content_'+i);
        }
        toggleTabActionBtns('', <?= empty($page->tabs) ? 1 : 0 ?>);
    });
    
    /**** FOR PAGES TABBED CONTENT ****/
    
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>