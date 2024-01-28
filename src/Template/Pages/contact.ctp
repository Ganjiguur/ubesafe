<?php
echo $this->Html->script("vue.min", ['block' => true]);
echo $this->Html->css("jquery.scrollbar", ['block' => 'top']);
echo $this->Html->script("jquery.scrollbar.min", ['block' => 'top']);

?>

<style>
  #map {
    height: 300px;
    width: 100%;
  }
  .branches>.branch:first-child {
    margin-top:0px;
  }
  .branch {
    background-color: #fff;
    padding:20px 25px;
    margin-top: 15px;
  }
  .branch h4 {
    margin-bottom: 10px!important;
    text-transform: uppercase;
  }
  .branch-iw .title>span,
  .branch h4>span {
    color: #8082a4;
    text-transform: initial;
    display:block;
    font-weight: 400;
  }
  .inner-nav>.scrollbar-macosx {
    max-width: 100%;
  }
  .nav-item span {
    display: none;
  }
  .inner-nav {
    margin-bottom: 15px;
  }
  .inner-nav-container {
    overflow: hidden;
    padding: 0 28px;
    border-top: 1px solid #ebebeb;
  }
  .branch-iw {
    max-width: 250px;
  }
  @media (max-width: 767px) {
    .branch-iw {
      max-width: 250px!important;
    }
    #map {
      height: 400px;
    }
    .branch.inner-item .info h4 {
      margin-top: 10px;
     }
  }
</style>

<?= $this->element('site/crumb', ['crumbs' => $crumblist, 'pageTitle' => $page->title]); ?>

<div class="maincontent">
  <div class="uk-container uk-container-center">
    <div class="maincontent_detail">
      <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
        <div class="uk-width ck-pagecontent" id="branches-app">
          <div id="map"></div>
          <div class="branches">
            <div class="inner-nav-container">
              <div class="inner-nav uk-hidden-small" data-uk-sticky="{media: 640}">
                <div class="scrollbar-macosx">
                <a v-bind:href="'#branch-' + branch.id" v-for="(branch, index) in filteredBranches" data-uk-smooth-scroll="{offset: 70}" class="nav-item" v-html="branch.name" v-bind:class="{'active':index==0}"></a>
                </div>
              </div>
            </div>
            <div class="branches uk-animation-fade" v-cloak="v-cloak" id="branch_list">
              <div v-for="branch in filteredBranches" class="branch inner-item" v-bind:id="'branch-' + branch.id">
                  <div class="uk-grid" data-uk-grid-match>
                    <div class="uk-width-medium-1-4" v-if="branch.image">
                      <div class="uk-cover-background uk-position-relative" v-bind:style="{'background-image': 'url(' + branch.image + ')'}" style="width:100%;height:100%;">
                        <img v-bind:src="branch.image"  class="uk-invisible">
                      </div>
                    </div>
                    <div v-bind:class="{'uk-width-medium-3-4':branch.image, 'uk-width-medium-1-1': !branch.image}">
                      <div class="uk-grid info">
                        <div class="uk-width-medium-1-1">
                          <h4 class="text-grape text-16" v-html="branch.name"></h4>
                        </div>
                        <div v-bind:class="{'uk-width-medium-1-2':branch.image, 'uk-width-medium-3-5': !branch.image}">
                          <div class="">
                            <p class="address" v-html="branch.address"></p>
                          </div>
                        </div>
                        <div v-bind:class="{'uk-width-medium-1-2':branch.image, 'uk-width-medium-2-5': !branch.image}">
                          <div class="meta">
                            <div class="meta-row" v-if="branch.phone">
                              <strong><?= __("Утас") ?>: </strong>
                              <a v-bind:href="'tel:' + branch.phone" class="text-grape">{{ branch.phone }}</a>
                            </div>
                            <div class="meta-row" v-if="branch.email">
                              <strong><?= __("Цахим шуудан") ?>: </strong>
                              <a v-bind:href="'mailto:' + branch.email" class="text-grape">{{ branch.email }}</a>
                            </div>
                            <div class="meta-row" v-if="branch.timetable" >
                              <span v-html="branch.timetable"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="uk-width ck-sidebar">
          <div class="maintext">
            <?= $page->html ?>
            <div style="max-width: 580px;">
              <?php
              if(!empty($page->forms)) {
                echo $this->element('site/form', [
                            'form' => $page->forms[0],
                            'formClass' => '',
                            'btnClass' => 'uk-button uk-button-small yellowbutton',
                            'inputClass' => 'uk-width-1-1 ck-input small',
                            'showTitle' => true,
                            'showLabel' => false,
                            'useGrid' => false,
                            'showBtnArrow' => true,
                            'titleClass' => 'uk-text-uppercase text-16 text-grape'
                            ]);
              }
              ?>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?= $this->element('site/footer_top', ['banners' => $page->banners]) ?>

<!--Bottom right screen popup banner-->
<?php
if (!empty($page->banners)) {
  foreach ($page->banners as $banner) {
      switch ($banner->type) {
        case 'popup_footer':
          echo $this->element('site/banner_side', ['banner' => $banner]);
          break;
        case 'popup_footer_left':
          echo $this->element('site/banner_side_left', ['banner' => $banner]);
          break;
        case 'popup_footer_center':
          echo $this->element('site/banner_side_center', ['banner' => $banner]);
          break;
        default:
          break;
      }
  }
}
?>

<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>
  var baseUrl = '<?= $this->Url->build('/', true); ?>';
  var phpBranches = <?= json_encode($branches, JSON_UNESCAPED_UNICODE) ?>;
  var map = null;
  var markers = [], markerIconBranch, markerIW, userIW;
  var mainBranchLocation = {lat: 47.911073032412, lng: 106.91369959259};
  var userLocation = null;
  var userMarker = null;

  $(function() {
    innerNavScroll();

    $(".inner-nav a.nav-item").on('mouseenter', function() {
      scrollToNavItem($(this));
    });
  });

  function showLocationError(error) {
    alert("<?= __("Уучлаарай таны байрлалын мэдээллийг авах боломжгүй байна.") ?>" + "\n\n" + (error.message || ""));
    console.log(error);
  }

  function getUserLocation(show_error) {
    show_error = typeof show_error !== 'undefined' ? show_error : true;
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(gotUserLocation, function(error){
        show_error && showLocationError(error);
      } );
    } else {
      show_error && showLocationError([]);
    }
  }

  function gotUserLocation(position) {
    userLocation = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    showUserLocation();
  }

  function showUserLocation() {
    if(userLocation != null && map != null) {
      userMarker && userMarker.setMap(null);
      userMarker = new google.maps.Marker({
          position: userLocation,
          map: map,
          animation: google.maps.Animation.DROP
        });

      google.maps.event.addListener(userMarker, 'click', function() {
        userIW.open(map, userMarker);
      });
    }
  }

  getUserLocation(false);

  function initMap() {
    var center = {lat: 47.911073032412, lng: 106.91369959259};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 14,
      center: center
    });

    userIW = new google.maps.InfoWindow({
        disableAutoPan: true,
        content: '<?= __("Таны байрлал") ?>'
    });

    markerIW = new google.maps.InfoWindow({
      pixelOffset: {width:0, height:0, maxWidth:250}
    });

    markerIconBranch = {
      url: baseUrl + 'img/pin.png',
      scaledSize: new google.maps.Size(30, 45), // scaled size
      origin: new google.maps.Point(0,0), // origin
      anchor: new google.maps.Point(30, 45) // anchor
    };

    google.maps.event.addListener(markerIW, 'domready', function(){
      google.maps.event.clearListeners(this, 'domready');
    });

    updateMapPins(phpBranches);
    openBranch();
    showUserLocation();
  }

  function getIcon(branch) {
    return markerIconBranch;
  }

  function updateMapPins(branches) {
    for (var i = 0; i < markers.length; i ++) {
      markers[i].setMap(null);
    }
    markers = [];
    for (var i = 0; i < branches.length; i ++) {
      var branch = branches[i];

      if(branch.latitude == null || branch.longitude == null) {
        continue;
      }

      var phoneString = "";
      var image = '';
      var content = '';
      var name = '<p class="title uk-text-uppercase text-semibold">' +branch.name+ '</p>';

      if(branch.phone && branch.phone !== '' && branch.phone !== null) {
        phoneString = '<a href="tel:'+baseUrl+'/property/'+branch.phone+'" class=""><strong><?= __("Утас") ?>:</strong> <span class="text-grape">' + branch.phone+'<span></a>';
      }

      var info = '<div class="info">' +branch.address+ '<br>' + phoneString + '<br><br>' + branch.timetable + '</div>';
      var style = "";
      if(branch.image) {
        image = '<div class="uk-cover-background uk-margin-right" style="background-image: url(\'' + branch.image + '\');width:100px;height:80px;float:left"></div>';
        style = "max-width:300px";
      }


       content = '<div class="branch-iw" style="'+style+'">' + name + image + info + '</div>';

      var marker = new google.maps.Marker({
        position: {lat: Number(branch.latitude), lng: Number(branch.longitude)},
        icon: getIcon(branch),
        content: content,
        branch_type: branch.type,
        branch: branch,
        map: map
      });

      google.maps.event.addListener(marker, 'click', function() {
        markerIW.setContent(this['content']);
        markerIW.open(map, this);
      });

      markers.push(marker);
    }
  }

  function openBranch() {
    if(map == null || !markers.length) {
      return;
    }
    var marker = markers[0];
    markerIW.setContent(marker['content']);
    markerIW.open(map, marker);
  }

  var vm = new Vue({
    el: '#branches-app',
    data: {
      vbranches: phpBranches,
      showMap:true,
      area: 0,
      workDay: 0,
      type: 'all',
      filteredBranches: phpBranches,
      closest: null,
      showing_openNow: false,
      showing_nearby: false
    },
    methods: {
      filter: function(showNearest, openNow) {
        showNearest = typeof showNearest !== 'undefined' ? showNearest : true;
        openNow = typeof openNow !== 'undefined' ? openNow : false;

        this.closest = null;
        var filtered = [];

        this.showing_nearby = false;
        this.showing_openNow = openNow;

        for (var i = 0; i < this.vbranches.length; i++) {
          var _branch = this.vbranches[i];

          //type
          if(this.type != 'all' && this.type != _branch['type']) continue;
          //area
          if((!this.area && _branch['area'] >= 10) || (this.area && this.area != _branch['area'])) continue;
          //workday
          if(!(this.workDay == 2 && (_branch['work_days'] == 2 || _branch['work_days'] == 4)) && !(this.workDay == 3 && !(_branch['work_days'] == 3 || _branch['work_days'] == 4)) && !(this.workDay == 4 && _branch['work_days'] > 1) && !(this.workDay == 0)) continue;

          if (openNow) {
            if(_branch['isOpen'] !== true) continue;
          }

          filtered.push(_branch);
        }

        this.filteredBranches = filtered;
        updateMapPins(filtered);

        showNearest && showNearestBranch(true);
      },
    }
  });

<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>

<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyA9JxzKBkv9-MGOSfmAgEiw0bxmvKUwe-Q&callback=initMap', ['block' => true, 'async'=>true, 'defer'=>true]); ?>
