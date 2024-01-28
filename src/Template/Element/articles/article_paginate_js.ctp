<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
    var loadingHtml = '<?= __("Please wait") ?> <i class="uk-icon-refresh uk-icon-spin"></i>';
    function showMore(button) {
      var btn = $(button);
      var dataLoading = btn.attr('data-loading');
      if (dataLoading == "true") {
        return;
      }

      btn.attr('data-loading', true);
      btn.attr('data-html', btn.html());
      btn.html(loadingHtml);

      var dataPage = parseInt(btn.attr('data-page')) + 1;
      var dataType = btn.attr('data-type');
      var dataContainerId = btn.attr('data-container-id');
      var dataTotalPage = parseInt(btn.attr('data-totalpage'));

      $.ajax({
        url: "<?= $url?>" + dataPage,
        type: "POST",
        data: {
          type: dataType,
          lang: "<?=$currentLang?>"
        },
        beforeSend: function(request) {
          request.setRequestHeader("X-CSRF-Token", '<?= $this->request->param('_csrfToken') ?>');
        }
      }).done(function(data) {
        btn.attr('data-page', dataPage);
        $("#" + dataContainerId).append(data).trigger('display.uk.check');
        if(dataTotalPage <= dataPage){
          btn.remove();
        }
      }).fail(function(error) {
          console.log(error);
      }).always(function() {
        btn.attr('data-loading', false);
        btn.html(btn.attr('data-html'));
      });
    }
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>