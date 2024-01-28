<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");

?>

<!-- Include Font Awesome. -->
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<!-- Include Editor style. -->
<?=
$this->Html->css([
    '../froala_editor/froala_style.min.css',
    '../froala_editor/froala_editor.pkgd.min.css'
        ], ['block' => true])
?>

<!-- Include Code Mirror style -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

<style>
  /*gridPlugin styles*/
  .fr-grid-insert-layer {
    width:200px!important;
  }

  .fr-grid-insert-layer .fr-grid-attr {
    float:right;
  }

  select.fr-grid-attr {
    min-width: 50px;
  }

  input[type=number].fr-grid-attr {
    width: 40px;
    padding: 3px;
  }
  
  #inlineStyle-1.fr-command.fr-btn+.fr-dropdown-menu .fr-dropdown-wrapper .fr-dropdown-content ul.fr-dropdown-list li a {
    color: inherit;
  }

</style>

<!-- Include Code Mirror. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

<!-- Include JS files. -->
<?= $this->Html->script('../froala_editor/froala_editor.pkgd.min.js', ['block' => true]) ?>

<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  var base_url = '<?= $this->AppView->getFullBaseUrl() ?>';
//  console.log($.FroalaEditor);
  var getFroalaDefaults = function (csrfToken, btns = null) {

    var uikit_attrs = ['data-uk-grid-margin', 'data-uk-grid-match', 'data-uk-dropdown', 'data-uk-tab', 'data-uk-modal', 'data-uk-offcanvas', 'data-uk-nav', 'data-uk-switcher', 'data-uk-switcher-item', 'data-uk-toggle', 'data-uk-scrollspy', 'data-uk-smooth-scroll', 'data-uk-lightbox', 'data-uk-autocomplete', 'data-uk-datepicker', 'data-uk-slider', 'data-uk-slider', 'data-uk-slider-item', 'data-uk-slideset', 'data-uk-slideset-item', 'uk-slideshow', 'data-uk-slideshow-item', 'data-uk-parallax', 'data-uk-filter', 'data-uk-accordion', 'data-uk-search', 'data-uk-nestable', 'data-uk-sortable', 'data-uk-sticky', 'data-uk-timepicker', 'data-uk-tooltip', 'i'];

    var defaultBtns = {
      xs: ['bold', 'italic', 'paragraphStyle', 'fontSize', 'undo', 'redo'],
      sm: ['bold', 'italic', 'underline', 'paragraphStyle', 'fontSize', 'insertLink', 'insertImage', 'insertTable', 'undo', 'redo', 'fullscreen'],
      md: ['bold', 'italic', 'underline', 'fontSize', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', 'paragraphStyle', '|', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', '|', 'insert_grid', 'clone_dn', 'insertAcc', 'embed', '|', 'html', 'fullscreen'],
      lg: ['bold', 'italic', 'underline', 'strikeThrough', 'fontFamily', 'fontSize', '|', 'subscript', 'superscript', 'color', 'specialCharacters', 'emoticons', '-', 'paragraphStyle', 'inlineStyle', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '|', 'insert_grid', 'clone_dn', 'insertAcc', 'embed', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', '|', 'print', 'help', 'html', 'fullscreen']
    };

    if (btns !== null) {
      defaultBtns.lg = btns;
      defaultBtns.md = btns;
    }

    var options = {
      saveInterval: 0,
      charCounterCount: false,
      pastePlain: true,
      placeholderText: 'Content',
      requestHeaders: {
        'X-CSRF-Token': csrfToken
      },
      toolbarButtons: defaultBtns.lg,
      toolbarButtonsMD: defaultBtns.md,
      toolbarButtonsSM: defaultBtns.sm,
      toolbarButtonsXS: defaultBtns.xs,
      videoInsertButtons: ['videoBack', '|', 'videoByURL', 'videoEmbed'],
      imageDefaultWidth: 0,
      tabSpaces: 4,
      lighboxImageUrl: base_url + '/img/nophoto.png',
      heightMin: 200,
      imageUploadURL: base_url + '/extends/ajaxUploadImage',
      // Set max image size to 5MB.
      imageMaxSize: 5 * 1024 * 1024,
      imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
      // Set the load images request type.
      imageManagerLoadMethod: "POST",
      imageManagerLoadURL: base_url + '/extends/ajaxLoadImages',
      imageManagerDeleteURL: base_url + '/extends/ajaxDeleteImage',
      fileUploadURL: base_url + '/extends/ajaxUploadFile',
      // Set max file size to 5MB.
      fileMaxSize: 40 * 1024 * 1024,
      // Allow to upload any file.
      fileAllowedTypes: ['*'],
      htmlAllowedAttrs: ['i'].concat($.FroalaEditor.DEFAULTS.htmlAllowedAttrs),
      //Not included plugins: Embedly, Image Aviary, Spell Checker
      pluginsEnabled: ['align', 'charCounter', 'codeBeautifier', 'codeView', 'colors', 'draggable', 'emoticons', 'entities', 'file', 'fontFamily', 'fontSize', 'fullscreen', 'help', 'image', 'imageManager', 'inlineStyle', 'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle', 'print', 'quickInsert', 'quote', 'save', 'specialCharacters', 'table', 'url', 'video', 'wordPaste', 'gridPlugin'],
      htmlAllowedEmptyTags: uikit_attrs.concat($.FroalaEditor.DEFAULTS.htmlAllowedEmptyTags),
      toolbarStickyOffset: 130,
      zIndex: 1250,
      lineBreakerTags: ['div.uk-grid', 'div.uk-overflow-container'].concat($.FroalaEditor.DEFAULTS.lineBreakerTags),
      paragraphStyles: {
        'uk-text-uppercase': 'Uppercase',
        'text-grape': 'Grape',
        'text-darkblue': 'Dark Blue',
        'text-grey': 'Grey',
        'text-dark': 'Dark',
        'text-lightblue': 'Light',
        'text-yellow': 'Yellow',
        'text-darkyellow': 'Dark Yellow',
        'text-10': 'Text 10px',
        'text-11': 'Text 11px',
        'text-12': 'Text 12px',
        'text-13': 'Text 13px',
        'text-13l': 'Text 13px (small line space)',
        'text-14': 'Text 14px',
        'text-16': 'Text 16px',
        'text-18': 'Text 18px',
        'text-20': 'Text 20px',
        'text-22': 'Text 22px',
        'text-24': 'Text 24px',
      },
      tableStyles: {
        "uk-table": 'Basic style (must have)',
        "uk-table-hover": 'Table hover',
        "data-table": 'Data Table (small spaces)',
        "uk-table-striped": 'Table striped',
        'fr-dashed-borders': 'Dashed Borders',
        'fr-alternate-rows': 'Alternate Rows',
        "rw-accordion": "Accordion table",
//        "rw-responsibility": "Social responsibility",
//        "rw-timeline-v": "Vertical Timeline table",
         "rw-timeline": "Timeline table",
//        "rw-hr-accordion": "Vacancy notice table",
        "rw-files": "Download files",
        "rw-reports": "Download files"
      },
      tableCellStyles: {
        'ck-head': 'Header cell',
        'uk-text-bold': 'Bold',
        'uk-text-uppercase': 'Uppercase',
        'fr-highlighted': 'Highlighted',
        'fr-thick': 'Thick',
        'accordion-open': 'Accordion open'
      },
      inlineStyles: {
        'Grape': '  color: #222d47;',
        'Blue': 'color: #0057a4;',
        'Dark blue': 'color: #1c1839;',
        'Light': 'color: #8082a4;',
        'Dark': 'color: #1b1b1b;',
        'Grey': 'color: #4c5763;',
        'Yellow': 'color: #617693;',
        'Dark yellow': 'color: #617693;',
        'Green': 'color: #1dcc92;',
        'Yellow': 'color: #ff433d;',
        'Uppercase': 'text-transform: uppercase;'
      },
      linkStyles: {
        'text-grape': 'Grape',
        'text-grey': 'Grey',
        'yellowbutton': 'Yellow button',
        'bluebutton': 'Blue button',
        'grapebutton': 'Grape button',
        'uk-text-bold': 'Bold',
        'uk-text-uppercase': 'Uppercase'
      },
      tableEditButtons: $.FroalaEditor.DEFAULTS.tableEditButtons.concat(['dupRow'])
    };

    return options;
  }

  /*** Gird plugin start ***/
  //If you use this plugin make sure following configurations:
  // 1. Add this option to editor option: lighboxImageUrl: base_url + '/img/nophoto.png'
  // 2. Add styles which is written above
  // 3. Add this button to editor buttons option: insert_grid
  // 4. Add this plugin to pluginsEnabled option: gridPlugin

  $(function () {
    // Define popup template.
    $.extend($.FroalaEditor.POPUP_TEMPLATES, {
      "gridPlugin.popup": '[_INPUT_LAYER_]'
    });

    // The grid popup is defined inside a plugin (new or existing).
    $.FroalaEditor.PLUGINS.gridPlugin = function (editor) {
      // Create grid popup.
      function initPopup() {

        var widthOptions = '<option value="2">2</option><option value="3" selected="selected">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="10">10</option>';
        var clazzOptions = '<option value="uk-grid-collapse">Зайгүй</option><option value="uk-grid-small">Жижиг</option><option value="uk-grid-medium" selected="selected">Дунд</option><option value="uk-grid-large">Том</option>';
        var typeOptions = '<option value="general">Энгийн</option><option value="lightbox">Зургийн галерей</option>';

        var inputLayer = '<div class="fr-input-line"><label for="fr-grid-insert-layer-width-' + editor.id + '">Дэлгэцийн хуваалт: </label><select id="fr-grid-insert-layer-width-' + editor.id + '" name="width" class="fr-grid-attr" tabIndex="' + ++g + '">' + widthOptions + '</select></div>';
        inputLayer += '<div class="fr-input-line"><label for="fr-grid-insert-layer-count-' + editor.id + '">Давтах тоо: </label><input id="fr-grid-insert-layer-count-' + editor.id + '" name="count" type="number" step="1" class="fr-grid-attr" tabIndex="' + ++g + '" value="3"></div>';
        inputLayer += '<div class="fr-input-line"><label for="fr-grid-insert-layer-clazz-' + editor.id + '">Хоорондын зай: </label><select id="fr-grid-insert-layer-clazz-' + editor.id + '" name="clazz" class="fr-grid-attr" tabIndex="' + ++g + '">' + clazzOptions + '</select></div>';
        inputLayer += '<div class="fr-input-line"><label for="fr-grid-insert-layer-type-' + editor.id + '">Төрөл: </label><select id="fr-grid-insert-layer-type-' + editor.id + '" name="type" class="fr-grid-attr" tabIndex="' + ++g + '">' + typeOptions + '</select></div>';
        inputLayer += '<div class="fr-action-buttons"><button class="fr-command fr-submit" role="button" data-cmd="gridInsert" href="#" tabIndex="' + ++g + '" type="button">Оруулах</button></div>';

        // Load popup template.
        var template = {
          input_layer: '<div class="fr-grid-insert-layer fr-layer fr-active" id="fr-grid-insert-layer-' + editor.id + '">' + inputLayer + '</div>'
        };

        // Create popup.
        var $popup = editor.popups.create('gridPlugin.popup', template);

        editor.popups.onHide('gridPlugin.popup', function () {
          $('.fr-grid-insert-layer select').prop('disabled', true);
        });

        return $popup;
      }

      // Show the popup
      function showPopup() {
        // Get the popup object defined above.
        var $popup = editor.popups.get('gridPlugin.popup');

        // If popup doesn't exist then create it.
        // To improve performance it is best to create the popup when it is first needed
        // and not when the editor is initialized.
        if (!$popup)
          $popup = initPopup();

        // Set the editor toolbar as the popup's container.
        editor.popups.setContainer('gridPlugin.popup', editor.$tb);

        // This will trigger the refresh event assigned to the popup.
        // editor.popups.refresh('gridPlugin.popup');

        // This grid popup is opened by pressing a button from the editor's toolbar.
        // Get the button's object in order to place the popup relative to it.
        var $btn = editor.$tb.find('.fr-command[data-cmd="insert_grid"]');

        // Set the popup's position.
        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);

        // Show the grid popup.
        // The button's outerHeight is required in case the popup needs to be displayed above it.
        $('.fr-grid-insert-layer select').prop('disabled', false);
        editor.popups.show('gridPlugin.popup', left, top, $btn.outerHeight());
      }

      // Hide the grid popup.
      function hidePopup() {
        editor.popups.hide('gridPlugin.popup');
      }

      function insert() {
        var widthClasses = {
          '2': 'uk-width-medium-1-2',
          '3': 'uk-width-medium-1-3',
          '4': 'uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4',
          '5': 'uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5',
          '6': 'uk-width-small-1-2 uk-width-medium-1-4 uk-width-large-1-6',
          '10': 'uk-width-small-1-3 uk-width-medium-1-5 uk-width-large-1-10',
        };

        var typeClasses = {
          'general': '',
          'lightbox': 'lightbox-grid'
        };

        var e = editor.popups.get("gridPlugin.popup"),
                width = e.find("select.fr-grid-attr[name=width]").val() || 2,
                count = Number(e.find("input.fr-grid-attr[name=count]").val() || 0),
                clazz = e.find("select.fr-grid-attr[name=clazz]").val() || "",
                type = e.find("select.fr-grid-attr[name=type]").val() || "general";

        if (!widthClasses[width]) {
          width = '2';
        }

        if (!typeClasses[type]) {
          type = 'general';
        }

        if (!count) {
          count = Number(width);
        }

        var gridClass = "uk-grid " + typeClasses[type] + " " + clazz;
        var gridAttrs = "data-uk-grid-margin";
        var el = $(editor.selection.endElement());
        if (el.is("br")) {
          el = el.parent();
        }

        var html = "";
        var content = "";

        switch (type) {
          case 'lightbox':
            content = '<img src="' + editor.opts.lighboxImageUrl + '">';
            break;
          default:
            content = '<p>Grid content</p>';
        }

        html = '<div class="' + gridClass + '" ' + gridAttrs + '>';
        for (var i = 0; i < count; i++) {
          html += '<div class="' + widthClasses[width] + '">' + content + '</div>';
        }
        html += '</div>';

        if (el.is("p, span")) {
          el.after(html);
        } else {
          el.append(html);
        }
        hidePopup();
      }

      // Methods visible outside the plugin.
      return {
        showPopup: showPopup,
        hidePopup: hidePopup,
        insertCallback: insert
      };
    };

    $.FroalaEditor.RegisterCommand('gridInsert', {
      undo: true,
      refreshAfterCallback: false,
      plugin: "gridPlugin",
      callback: function () {
        this.gridPlugin.insertCallback();
      },
    });

    // Define an icon and command for the button that opens the grid popup.
    $.FroalaEditor.DefineIcon('grid', {NAME: 'th'});
    $.FroalaEditor.RegisterCommand('insert_grid', {
      title: 'Grid Layout',
      icon: 'grid',
      undo: false,
      focus: false,
      plugin: 'gridPlugin',
      callback: function () {
        this.gridPlugin.showPopup();
      }
    });

    /*** Gird plugin end ***/

    //Duplicate options: entire grid and grid item
    $.FroalaEditor.DefineIcon('clone_dn', {NAME: 'clone'});
    $.FroalaEditor.RegisterCommand('clone_dn', {
      title: 'Duplicate options',
      type: 'dropdown',
      focus: false,
      undo: true,
      refreshAfterCallback: false,
      options: {
        'griditem': 'Duplicate current grid item',
        'grid': 'Duplicate current entire grid'
      },
      callback: function (cmd, val) {
        switch (val) {
          case 'griditem':
            var el = $(this.selection.element());
            var selector = "div[class^='uk-width']";
            var item = undefined;
            if (el.is(selector)) {
              item = el;
            } else {
              var parents = el.parents(selector);
              if (parents.length > 0) {
                item = $(parents[0]);
              }
            }

            if (item !== undefined && item.parent().hasClass('uk-grid') && $.contains(this.$el[0], item[0])) {
              item.clone().insertAfter(item);
            } else {
              alert("Oops. No grid item is found!");
            }
            break;
          case 'grid':
            var el = $(this.selection.element());
            var selector = "div.uk-grid";
            var item = undefined;
            if (el.is(selector)) {
              item = el;
            } else {
              var parents = el.parents(selector)
              if (parents.length > 0) {
                item = $(parents[0]);
              }
            }

            if (item !== undefined && $.contains(this.$el[0], item[0])) {

              item.clone().insertAfter(item).before('<p><br></p>');
            } else {
              alert("Oops. No grid is found!")
            }
            break;
          default:
            break;
        }
      },
    });

    //Insert Accordion
    $.FroalaEditor.DefineIcon('insertAcc', {NAME: 'plus-square'});
    $.FroalaEditor.RegisterCommand('insertAcc', {
      title: 'Insert items',
      type: 'dropdown',
      focus: false,
      undo: true,
      refreshAfterCallback: false,
      options: {
        'accordion': 'Accordion'
      },
      callback: function (cmd, val) {
        switch (val) {
          case 'accordion':
            this.table.insert(2, 1);

            var el = $(this.selection.element());
            var item = undefined;
            var selector = 'table';

            if (el.is(selector)) {
              item = el;
            } else {
              var parents = el.parents(selector)
              if (parents.length > 0) {
                item = $(parents[0]);
              }
            }

            if (item !== undefined && $.contains(this.$el[0], item[0])) {
              item.addClass('rw-accordion uk-table-striped');
            } else {
              alert("Oops. Please try again!")
            }
            break;
          default:
            break;
        }
      }
    });

    //Insert embed code
    $.FroalaEditor.DefineIcon('embed', {NAME: 'share-alt-square'});
    $.FroalaEditor.RegisterCommand('embed', {
      title: 'Embed code оруулах',
      focus: false,
      undo: true,
      refreshAfterCallback: false,
      callback: function () {
        var el = $(this.selection.endElement());
        if (el.is("br")) {
          el = el.parent();
        }
        UIkit.modal.prompt("Embed code-ийг оруулна уу.", "", function (html) {
          html = html.replace(new RegExp("\<script.*script>", "gm"), "");
          if (el.is("p, span")) {
            el.after(html);
          } else {
            el.append(html);
          }
        });
      }
    });

    //Duplicate table row
    $.FroalaEditor.DefineIcon('dupRow', {NAME: 'clone'});
    $.FroalaEditor.RegisterCommand('dupRow', {
      title: 'Duplicate this row',
      focus: true,
      undo: true,
      refreshAfterCallback: true,
      callback: function () {
        var el = $(this.selection.element());
        var item = undefined;
        var selector = 'tr';
        if (el.is(selector)) {
          item = el;
        } else {
          var parents = el.parents(selector);
          if (parents.length > 0) {
            item = $(parents[0]);
          }
          ;
        }

        if (item !== undefined && $.contains(this.$el[0], item[0])) {
          item.clone().insertAfter(item);
        } else {
          alert("Oops. No table row is found!");
        }
      }
    });
  });

  var REWRITE = REWRITE || (function () {
    var _args = {}; // private

    return {
      init: function (Args) {
        _args = Args;
      },
      bindEditor: function () {
        var editorInitialized = false;

        var cid = _args[0];
        var options = _args[1];
        var isInline = _args[2];
        var selector = _args[3] || "#rewrite";

        var allImages = {};

        function initEditor() {
          if (editorInitialized)
            return;
          var editor = $(selector).froalaEditor(options);
          /* Custom stylizers */
          editor.on('froalaEditor.commands.after', function (e, editor, cmd, param1, param2) {
            var frView = $(editor.el);
            switch (cmd) {
              case 'tableInsert':
                frView.find('table:not(.uk-table)').addClass('uk-table').wrap('<div class="uk-overflow-container">');
                break;
              case 'tableRemove':
                frView.find('div.uk-overflow-container:empty').remove();
                break;
              case 'formatUL':
                frView.find('ul').addClass('ck-list');
                break;
              default:
                break;
            }
          })
//          .on('froalaEditor.paste.after', function (e, editor) {})
          /* END OF Custom stylizers */
          .on('froalaEditor.image.uploaded', function (e, editor, response) {
            // Image was uploaded to the server.
            var result = JSON.parse(response);
            if (result.success && isInline) {
              //register inserted image to all images
              allImages[result.link] = result.size.toString();
            }
          })
          .on('froalaEditor.image.error', function (e, editor, error, response) {
            // Response contains the original server response to the request if available.
            var msg = "Failed to upload.";
            if (error.code == 1)
              msg += " Bad link.";
            else if (error.code == 2)
              msg += " No link in upload response.";
            else if (error.code == 3) {
              var result = JSON.parse(response);
              if (result.msg)
                msg += " " + result.msg;
              else
                msg += " Error during image upload.";
            } else if (error.code == 4)
              msg += " Parsing response failed.";
            else if (error.code == 5)
              msg += " Image too large.";
            else if (error.code == 6)
              msg += " Invalid image type.";
            else if (error.code == 7)
              msg += " Image can be uploaded only to same domain in IE 8 and IE 9.";
            alert(msg);
          })
          .on('froalaEditor.imageManager.imagesLoaded', function (e, editor, response) {
            // Image was uploaded to the server.
            if (response.success) {
            } else {
              console.log(response.msg);
            }
          })
          .on('froalaEditor.imageManager.error', function (e, editor, error, response) {
            var msg = "Error! ";
            if (error.code == 10)
              msg += " Bad link. One of the returned image links cannot be loaded.";
            else if (error.code == 11)
              msg += " Bad link. Error during request.";
            else if (error.code == 12)
              msg += " Bad link. Missing imagesLoadURL option.";
            else if (error.code == 13)
              msg += " Bad link. Parsing response failed.";
            alert(msg);
          })
          .on('froalaEditor.imageManager.imageDeleted', function (e, editor, data) {
            // Do something after the image was deleted from the image manager.
            var result = JSON.parse(data);
            if (result.success) {
              //register inserted image to all images
            } else {
              console.log(result.msg);
            }
          })
          .on('froalaEditor.file.uploaded', function (e, editor, response) {
            // File was uploaded to the server.
            var result = JSON.parse(response);
            if (result.success) {
            } else {
              console.log(result.msg);
            }
          })
          .on('froalaEditor.file.inserted', function (e, editor, $file, response) {
            var result = JSON.parse(response);
            if (result.success) {
              $file.attr('data-size', result['size']);
//                      $file.attr('data-type', result['type']);
            }
          })
          .on('froalaEditor.file.error', function (e, editor, error, response) {
            // Response contains the original server response to the request if available.
            var msg = "Failed to upload.";
            if (error.code == 1)
              msg += " Bad link.";
            else if (error.code == 2)
              msg += " No link in upload response.";
            else if (error.code == 3)
              msg += " Error during file upload.";
            else if (error.code == 4)
              msg += " Parsing response failed.";
            else if (error.code == 5)
              msg += " File too large.";
            else if (error.code == 6)
              msg += " Invalid file type.";
            else if (error.code == 7)
              msg += " File can be uploaded only to same domain in IE 8 and IE 9.";
            alert(msg);
          });
          editorInitialized = true;
        }

        initEditor();
      }
    };
  }());

<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>