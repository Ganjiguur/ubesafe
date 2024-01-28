<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  var opt = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>');

  function toggleSpecActionBtns(lang, hidden) {
    if (hidden) {
      $("#specs_init" + lang).show();
      $("#specs_destroy" + lang).hide();
      $("#spec_actions" + lang).hide();
    } else {
      $("#specs_init" + lang).hide();
      $("#specs_destroy" + lang).show();
      $("#spec_actions" + lang).show();
    }
  }

  function initEditor(selector) {
    opt['placeholderText'] = $(selector).attr("placeholder");
    REWRITE.init([null, opt, false, selector]);
    REWRITE.bindEditor();
  }

  function addSpec(lang) {
    UIkit.modal.prompt("Spec Name:", 'New spec', function (specName) {
      if (specName.length === 0) {
        return;
      }
      var num = Number($("#specs_header" + lang).attr('rw-spec-count'));
      $("#specs_header" + lang).attr('rw-spec-count', num+1);
      $("#specs_header" + lang).append('<li class="spec_head_'+num+'"><a href="#"><span id="specsn' + lang + '' + num + '">' + specName + '</span> <button onclick="editSpec(\'' + lang + '\', ' + num + ')" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button></a></li>');
      addNewSpecContent(lang, specName, num);
      initEditor('.specs_content' + lang + '_' + num);
    });
  }

  function addNewSpecContent(lang, specName, num) {
    var elName = $('<input>', {
      id: 'specs' + lang + '' + num,
      type: 'hidden',
      name: 'specs' + lang + '[' + num + '][name]',
      value: specName
    });
    var elContent = $('<textarea/>', {
      name: 'specs' + lang + '[' + num + '][content]',
      class: 'specs_content' + lang + '_' + num,
      placeholder: '\'' + specName + '\' spec content'
    });
    var elTitle = $('<input>', {
      type: 'text',
      name: 'specs' + lang + '[' + num + '][title]',
      class: 'uk-width-1-1',
    });
    var elType = $('<select/>', {
      name: 'specs' + lang + '[' + num + '][type]',
      class: 'uk-width-1-1',
    }).html('<option value="1">Show only on comparison</option><option value="2">Show only on brief introduction</option><option value="3">Show on both</option>');
    
    var settingsPart = '<div class="uk-grid uk-margin-bottom uk-form"><div class="uk-width-1-2">' + elTitle[0].outerHTML + '</div><div class="uk-width-1-2">' + elType[0].outerHTML + '</div></div>';
    
    var elFooter = '<div class="uk-text-right"><button onclick="removeSpec(\'' + lang + '\', ' + num + ')" class="md-btn md-btn-icon uk-margin-top" type="button"><i class="uk-icon-remove"></i> <?= __("Remove this spec") ?></button></div>';
    
    var content = $('<li/>', {class:'spec_body_'+num}).append(elName, settingsPart, elContent, elFooter);
    $("#specs_content" + lang).append(content);
  }

  function editSpec(lang, num) {
    var nameInput = $('#specs' + lang + '' + num);
    UIkit.modal.prompt("Spec Name:", nameInput.val(), function (specName) {
      if (specName.length == 0) {
        return;
      }
      nameInput.val(specName);
      $('#specsn' + lang + '' + num).text(specName);
    });
  }

  function removeSpec(lang, num) {
    if ($("#specs_content" + lang).children().length > 1) {
      UIkit.modal.confirm("<?= __("Та энэ TAB-ыг хасах гэж байгаадаа итгэлтэй байна уу?") ?> \n <?= __("Буцаах боломжгүй болохыг анхаарна уу.") ?>", function () {
        if(num === -1) {
          $("#specs_header" + lang).children('li:not(.uk-tab-responsive)').last().remove();
          $("#specs_content" + lang).children('li').last().remove();
        } else {
          $("#specs_header" + lang).children('.spec_head_'+num).remove();
          $("#specs_content" + lang).children('.spec_body_'+num).remove();
        }
      });
    } else {
      destroySpecs(lang);
    }
  }

  function initSpecs(lang) {
    UIkit.modal.prompt("Spec Name:", 'New spec', function (specName) {
      if (specName.length == 0) {
        return;
      }
      var $specHeader = $('<ul/>')
              .attr("id", "specs_header" + lang)
              .attr('rw-spec-count', '1')
              .attr("data-uk-tab", "{connect:'#specs_content" + lang + "', swiping:false}")
              .addClass("uk-tab")
              .html('<li><a href="#"><span id="specsn' + lang + '' + 0 + '">' + specName + '</span> <button onclick="editSpec(\'' + lang + '\', ' + 0 + ')" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button></a></li>');

      var $specContent = $('<ul/>')
              .attr("id", "specs_content" + lang)
              .addClass("uk-switcher uk-margin");
      $("#specs_container" + lang).html("");
      $("#specs_container" + lang).append($specHeader, $specContent);
      addNewSpecContent(lang, specName, 0);
      toggleSpecActionBtns(lang, false);
      initEditor('.specs_content' + lang + '_0');
    });
  }

  function destroySpecs(lang) {
    UIkit.modal.confirm("<?= __("Та TABBED контентийг бүхлээр нь хасах гэж байгаадаа итгэлтэй байна уу?") ?> \n <?= __("Буцаах боломжгүй болохыг анхаарна уу.") ?>", function () {
          $("#specs_container" + lang).html('');
          toggleSpecActionBtns(lang, true);
        });
      }
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>