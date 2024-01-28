<?php 
$sessionTimeout = $this->AppView->getSiteMeta('Session.timeout');
if(empty($sessionTimeout)) {
  $sessionTimeout = 60;
}
?>

<script type="text/javascript">
  var sesstionTimeout = <?= (int)$sessionTimeout ?>; //minutes

  $(function () {
    // append modal to <body>
    $body.append('<div class="uk-modal" id="modal_idle">' +
            '<div class="uk-modal-dialog">' +
            '<div class="uk-modal-header">' +
            '<h3 class="uk-modal-title">Систем хаагдах гэж байна!</h3>' +
            '</div>' +
            '<p>Та хэсэг хугацаанд идэвхгүй байсан тул системийн аюулгүй байдлын үүднээс таныг системээс автоматаар гаргах юм.</p>' +
            '<p>Хэрвээ та системээс гаралгүй ажлаа үргэлжилүүлэх хүсэлтэй байвал "Би ажиллаж байна" товч дээр дарна уу.</p>' +
            '<p>Та <span class="uk-text-bold md-color-red-500" id="sessionSecondsRemaining"></span> секундын дараа системээс автоматаар гарна.</p>' +
            '<div class="uk-modal-footer uk-text-right">' +
            '<button id="staySigned" type="button" class="md-btn md-btn-flat uk-modal-close md-btn-flat-primary">Би ажиллаж байна</button><button type="button" class="md-btn md-btn-flat md-btn-flat-danger" id="logoutSession">Гарах</button>' +
            '</div>' +
            '</div>' +
            '</div>');

    var modal = UIkit.modal("#modal_idle", {
      bgclose: false
    }),
            session = {
              //Logout Settings
              inactiveTimeout: (sesstionTimeout - 3) * 60 * 1000, //(ms) The time until we display a warning message
              warningTimeout: 30 * 1000, //(ms) The time until we log them out
              minWarning: 5000, //(ms) If they come back to page (on mobile), The minumum amount, before we just log them out
              warningStart: null, //Date time the warning was started
              warningTimer: null, //Timer running every second to countdown to logout
              autologout: {
                logouturl: "<?= $this->Url->build('/logout') ?>"
              },
              logout: function () {       //Logout function once warningTimeout has expired
                clearTimeout(session.warningTimer);
<?php if (!$this->AppView->getSiteMeta('debug')) : ?>
  //                  window.location = session.autologout.logouturl;
<?php endif; ?>
              }
            },
            $sessionCounter = $('#sessionSecondsRemaining').html(session.warningTimeout / 1000);

    $(document).on("idle.idleTimer", function (event, elem, obj) {
      //Get time when user was last active
      var diff = (+new Date()) - obj.lastActive - obj.timeout,
              warning = (+new Date()) - diff;

      //On mobile js is paused, so see if this was triggered while we were sleeping
      if (diff >= session.warningTimeout || warning <= session.minWarning) {
        modal.hide();
      } else {
        //Show dialog, and note the time
        $sessionCounter.html(Math.round((session.warningTimeout - diff) / 1000));
        modal.show();
        session.warningStart = (+new Date()) - diff;

        //Update counter downer every second
        session.warningTimer = setInterval(function () {
          var remaining = Math.round((session.warningTimeout / 1000) - (((+new Date()) - session.warningStart) / 1000));
          if (remaining >= 0) {
            $sessionCounter.html(remaining);
          } else {
            session.logout();
          }
        }, 1000);
      }
    });

    $body.on('click', '#staySigned', function () {//User clicked ok to stay online
      clearTimeout(session.warningTimer);
      modal.hide();
      updateSession();
    })
            .on('click', '#logoutSession', function () {//User clicked logout
              session.logout();
            });

    //Set up the timer, if inactive for 12 minutes log them out
    $(document).idleTimer(session.inactiveTimeout);

    window.setInterval(function () {
      if (!modal.isActive()) {
        updateSession();
      }
    }, 10 * 60 * 1000);


    function updateSession() {
      $.ajax({
        url: "<?= $this->Url->build(["controller" => "AppUsers", "action" => "ajaxUpdateSession"]) ?>",
        type: "POST", beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');
        }
      }).fail(function (result) {
        //TODO
        //Inform user to save data from doing any action because use may be logged out from the server
//        if(result.status === 403) {
//          session.logout();
//        }
      });
    }
  });
</script>