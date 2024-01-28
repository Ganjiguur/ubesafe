<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>

$(function () {
    // init permission selectize
    $('#permission').selectize({
        plugins: {
            'remove_button': {
                label: ''
            }
        },
        maxItems: null,
        valueField: 'value',
        labelField: 'title',
        searchField: 'title',
        create: false,
        onDropdownOpen: function($dropdown) {
            $dropdown
                .hide()
                .velocity('slideDown', {
                    begin: function() {
                        $dropdown.css({'margin-top':'0'})
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
                })
        }
    });
    $('#permission').next().children('.selectize-input').find('input').attr('name','selectize_permission');
    <?php if($AppUsers->role != 'moderator'):?>
        $('#permission').closest('.uk-form-row').hide();
    <?php endif;?>
    $('#role').change(function(){
        if(this.value=='moderator'){
            $('#permission').closest('.uk-form-row').show();
        }else{
            $('#permission').closest('.uk-form-row').hide();
        }
    });
})

<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>