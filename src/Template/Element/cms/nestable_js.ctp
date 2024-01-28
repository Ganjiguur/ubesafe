<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
var nestableOrderChanged = false;
$(function () {
  UIkit.on('change.uk.nestable', function(event, sortable_obj, dragged_element, action){
    orderChanged();
  });

  function orderChanged() {
    if(nestableOrderChanged) {
      return;
    }
    $('.delete-btn').remove();
    nestableOrderChanged = true;
    $("#btn_save_order button").show();
  }
});

var orders = {};

function getNestableOrders(parentEl, parent, torder, tlevel) {
  parentEl.children('li').each(function() {
    var el = $(this);
    var id = el.attr("rw-data-id");
    orders[id] = {
      'parent': parent,
      'lft': torder++,
      'level': tlevel
    };
    var childItem = el.children('ul');
    if(childItem && childItem.length > 0) {
      torder = getNestableOrders($(childItem[0]), id, torder, tlevel + 1);
    }
    orders[id]['rght'] = torder++;
  });
  return torder;
}

var savingOrder = false;
function saveOrder() {
  if(savingOrder) {
    return;
  }
  orders = {}
  savingOrder = true;
  $("#btn_save_order i").show();

  getNestableOrders($("#nestable_items"), null, 1, 0);

  $.ajax({
      url: "<?= $this->Url->build(["action" => "ajaxSaveOrder"]) ?>",
      type: "POST",
      dataType: "json",
      beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');
      },
      data: {"orders": orders}
  }).done(function (data) {
      $("#btn_save_order button").hide();
      $("#btn_save_order span").fadeIn().delay(1500).fadeOut('slow');
      refreshPage();
  }).fail(function (data) {
      alert(data.statusText);
      console.log(data);
  }).always(function() {
    $("#btn_save_order i").hide();
    savingOrder = false;
  });
}

function refreshPage() {
  var url = location.href;
  var sep = '?';
  if(url.indexOf("?") > 0) {
    sep = '&';
  }
  location.href = url + sep + 'recover=true';
}
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>