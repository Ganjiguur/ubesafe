<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  var opt = getFroalaDefaults('<?= $this->request->param('_csrfToken') ?>');

  function toggleTabActionBtns(lang, hidden) {
    if (hidden) {
      $("#tabs_init" + lang).show();
      $("#tabs_destroy" + lang).hide();
      $("#tab_actions" + lang).hide();
    } else {
      $("#tabs_init" + lang).hide();
      $("#tabs_destroy" + lang).show();
      $("#tab_actions" + lang).show();
    }
  }

  function initEditor(selector) {
    opt['placeholderText'] = $(selector).attr("placeholder");
    REWRITE.init([null, opt, false, selector]);
    REWRITE.bindEditor();
  }

  function addTab(lang) {
    UIkit.modal.prompt("Tab Name:", 'New tab', function (tabName) {
      if (tabName.length === 0) {
        return;
      }
      var num = Number($("#tabs_header" + lang).attr('rw-tab-count'));
      $("#tabs_header" + lang).attr('rw-tab-count', num+1);
      $("#tabs_header" + lang).append('<li class="tab_head_'+num+'"><a href="#"><span id="tabsn' + lang + '' + num + '">' + tabName + '</span> <button onclick="editTab(\'' + lang + '\', ' + num + ')" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button></a></li>');
      addNewTabContent(lang, tabName, num);
      initEditor('.tabs_content' + lang + '_' + num);
    });
  }

  function addNewTabContent(lang, tabName, num) {
    var elName = $('<input>', {
      id: 'tabs' + lang + '' + num,
      type: 'hidden',
      name: 'tabs' + lang + '[' + num + '][name]',
      value: tabName
    });
    var elContent = $('<textarea/>', {
      name: 'tabs' + lang + '[' + num + '][content]',
      class: 'tabs_content' + lang + '_' + num,
      placeholder: '\'' + tabName + '\' tab content'
    });
    var elFooter = '<div class="uk-text-right"><button onclick="removeTab(\'' + lang + '\', ' + num + ')" class="md-btn md-btn-icon uk-margin-top" type="button"><i class="uk-icon-remove"></i> <?= __("Remove this tab") ?></button></div>';
    
    var content = $('<li/>', {class:'tab_body_'+num}).append(elName, elContent, elFooter);
    $("#tabs_content" + lang).append(content);
  }

  function editTab(lang, num) {
    var nameInput = $('#tabs' + lang + '' + num);
    UIkit.modal.prompt("Tab Name:", nameInput.val(), function (tabName) {
      if (tabName.length == 0) {
        return;
      }
      nameInput.val(tabName);
      $('#tabsn' + lang + '' + num).text(tabName);
    });
  }

  function removeTab(lang, num) {
    if ($("#tabs_content" + lang).children().length > 1) {
      UIkit.modal.confirm("<?= __("Та энэ TAB-ыг хасах гэж байгаадаа итгэлтэй байна уу?") ?> \n <?= __("Буцаах боломжгүй болохыг анхаарна уу.") ?>", function () {
        if(num === -1) {
          $("#tabs_header" + lang).children('li:not(.uk-tab-responsive)').last().remove();
          $("#tabs_content" + lang).children('li').last().remove();
        } else {
          $("#tabs_header" + lang).children('.tab_head_'+num).remove();
          $("#tabs_content" + lang).children('.tab_body_'+num).remove();
        }
      });
    } else {
      destroyTabs(lang);
    }
  }

  function initTabs(lang) {
    UIkit.modal.prompt("Tab Name:", 'New tab', function (tabName) {
      if (tabName.length == 0) {
        return;
      }
      var $tabHeader = $('<ul/>')
              .attr("id", "tabs_header" + lang)
              .attr('rw-tab-count', '1')
              .attr("data-uk-tab", "{connect:'#tabs_content" + lang + "', swiping:false}")
              .addClass("uk-tab")
              .html('<li><a href="#"><span id="tabsn' + lang + '' + 0 + '">' + tabName + '</span> <button onclick="editTab(\'' + lang + '\', ' + 0 + ')" class="uk-button uk-button-small uk-button-link"><i class="uk-icon-edit"></i></button></a></li>');

      var $tabContent = $('<ul/>')
              .attr("id", "tabs_content" + lang)
              .addClass("uk-switcher uk-margin");
      $("#tabs_container" + lang).html("");
      $("#tabs_container" + lang).append($tabHeader, $tabContent);
      addNewTabContent(lang, tabName, 0);
      toggleTabActionBtns(lang, false);
      initEditor('.tabs_content' + lang + '_0');
    });
  }

  function destroyTabs(lang) {
    UIkit.modal.confirm("<?= __("Та TABBED контентийг бүхлээр нь хасах гэж байгаадаа итгэлтэй байна уу?") ?> \n <?= __("Буцаах боломжгүй болохыг анхаарна уу.") ?>", function () {
          $("#tabs_container" + lang).html('');
          toggleTabActionBtns(lang, true);
        });
      }
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>