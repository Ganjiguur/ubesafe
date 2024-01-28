<?php  
  $locale = $currentLang == 'mn' ? '' : '_en';
  $contact = $this->AppView->getSiteMeta('contact-phone', false);
  $isHome = !isset($page) ? false : $page->template == 'home' ? true : false;
  $ages = [__('0-4 настай, Нярайгаас 13 хүртэл жинтэй'), __('9 сар - 4 нас хүртэл, 9-18 кг жинтэй'), __('5 сар - 4 нас хүртэл, 0-25 кг жинтэй'), __('6 хүртэл нас, 12-24 кг жинтэй'), __('12 хүртэл, 22-36 кг жинтэй')];

?>
<?php if($isHome) : ?>
  <?php if(!$isHome) :?>
  <div class="header-white-bg" id="header-menu" data-uk-sticky="{ top:-350, animation: 'uk-animation-slide-top'}">
      <div class="uk-container uk-container-center container-margin">
          <div class="uk-clearfix">
              <div class="uk-float-left margin-bottom-25">
                  <a href="/">
                      <?= $this->Html->image('logo.png', ['class' => 'logo-white-bg']) ?>
                  </a>
              </div>
              <a class="uk-visible-small uk-float-right" href="#showmobilemenu" data-uk-offcanvas style="margin-top: 19px"><i
                      class="uk-icon-navicon uk-icon-medium" style="margin-top: 9px; margin-right: 10px;"></i></a>
              <div class="uk-float-left uk-hidden-small menu-list">
                  <a href="<?= $this->Url->build('/home')?>"><?= __("Нүүр"); ?></a>
                  <a href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a>
                  <a href="<?= $this->Url->build('/news')?>"><?= __("Мэдээ") ?></a>
              </div>
              <div class="uk-float-right" style="margin-top:21px">
                  <a href="" class="uk-hidden-small">
                          <?= $this->Html->image('icons/phone-black.png', ['class' => 'btn-logo'])?>
                          <?= __("Холбогдох: ") . $contact;  ?></a>
                  <a target="_blank" href="#send-donate" data-uk-modal class="uk-hidden-small uk-button uk-button-large btn-blue uk-text-center"><?= $this->Html->image('icons/present-white.png', ['class' => 'btn-logo'])?><?= __('Хандивлах')?>
                          </a>
              </div>
          </div>
          <div id="showmobilemenu" class="uk-offcanvas">
              <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
                  <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                      <li><a href="<?= $this->Url->build('/home')?>"><?= __("Нүүр")?></a></li>
                      <li><a href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a></li>
                      <li><a href="<?= $this->Url->build('/news')?>"><?= __("Мэдээ")?></a></li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
<?php endif;?>
  <div class="uk-cover-background header" style="background-image: url(img/header-bg.png);">
      <div class="uk-container uk-container-center">
          <div class="uk-clearfix">
              <div class="uk-float-left home-menu">
                  <a href="<?= $this->Url->build('/home')?>">
                      <?= $this->Html->image('white_logo.png', ['class' => 'logo']) ?>
                  </a>
                  <a href="<?= $this->Url->build('/home')?>" class="uk-hidden-small"><?= __("Нүүр"); ?></a>
                  <a href="<?= $this->Url->build('/about')?>" class="uk-hidden-small"><?= __("Төслийн тухай")?></a>
                  <a href="<?= $this->Url->build('/news')?>" class="uk-hidden-small"><?= __("Мэдээ") ?></a>
            </div>
            <a class="uk-visible-small uk-float-right" href="#showmobilemenuhome" data-uk-offcanvas style="margin-top: 19px"><i
                          class="uk-icon-navicon uk-icon-medium" style="margin-top: 13px; margin-right: 10px; color:#fff;"></i></a>
              <div class="uk-float-right home-menu uk-hidden-small" style="margin-top:35px;">
                  <a href=""><img src="img/icons/phone.png"
                          style="margin-right:8px; padding-bottom: 6px;"><?= __("Холбогдох: ") . $contact;  ?></a>
                  <a arget="_blank" href="#send-donate" data-uk-modal class="uk-hidden-medium uk-button uk-button-large btn-transplant-white uk-text-center"><img
                          src="img/icons/present.png" style="margin-right:8px; padding-bottom: 6px;"><span
                          class=""><?= __('Хандивлах')?></span></a>
              </div>
              <div id="showmobilemenuhome" class="uk-offcanvas">
                  <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
                      <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                          <li><a href="<?= $this->Url->build('/home')?>"><?= __("Нүүр")?></a></li>
                          <li><a href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a></li>
                          <li><a href="<?= $this->Url->build('/news')?>"><?= __("Мэдээ")?></a></li>
                      </ul>
                  </div>
              </div>
          </div>
          <div class="" style="margin-top:234px; margin-bottom:125px;">
              <h1 class="home-title uk-text-uppercase">Амин <br>хариуцлага<h1>
              <p class="home-description">Автомашины хүүхдийн суудлын <br>хэрэглээг нэмэгдүүлэх тасөл</p>
          </div>
          <div id="donate-app" class="margin-bottom-120 uk-hidden-small">
              <div class="margin-bottom-10">
                <button v-on:click="toggleDonate(true)" class="uk-button uk-button-link"
                      v-bind:class="{'uk-active':showDonate}" style="margin-left:-12px">
                      <?= __("Хамгаалалтын суудал авах") ?></button>
                <span class="slash">/</span><button v-on:click="toggleDonate(false)" class="uk-button uk-button-link"
                      v-bind:class="{'uk-active':!showDonate}"> <?= __("Хамгаалалтын суудал хандивлах") ?></button>
              </div>
              <div class="uk-grid uk-animation-fade form-white-bg" style="height:100px; margin-left:0px;" v-show="!showDonate">
                  <div class="uk-width-1-1 uk-width-medium-3-10 margin-top-20">
                      <label class="dropdown-title"><?= __('Таны хүүхдийн нас, жин') ?></label><br>
                      <select class="uk-button-dropdown form-dropdown">
                              <?php foreach($ages as $age) : ?>
                                <option class="dropdown-item"><?= $age ?></option>
                              <?php endforeach;?>
                        </select>
                  </div>
                  <div class="uk-width-1-1 uk-width-medium-1-5 margin-top-20">
                    <div class="vertical-line"></div>
                    <label class="dropdown-title"><?= __('Таны овог, нэр') ?></label><br>
                    <input class="form-input uk-input" type="text" placeholder="Нэр">
                  </div>
                  <div class="uk-width-1-1 uk-width-medium-1-5 margin-top-20">
                    <div class="vertical-line"></div>
                    <label class="dropdown-title"><?= __('Холбоо барих утас') ?></label><br>
                    <input class="form-input uk-input" type="text" placeholder="Утас">
                  </div>
                  <div class="uk-width-1-1 uk-width-medium-3-10 uk-text-center" style="padding-left:62px;">
                      <a target="_blank" href="#send-donate" data-uk-modal class="uk-button uk-button-large request-btn uk-text-center">
                        <?= $this->Html->image('icons/request-btn.png', ['class' => 'btn-request-logo uk-text-center']) ?>
                        <?= __('Хандивлах')?>
                      </a>
                  </div>
              </div>
              <div class="uk-grid uk-animation-fade form-white-bg" style="height:100px; margin-left:0px;" v-show="showDonate">
                <div class="uk-width-1-1 uk-width-medium-3-10 margin-top-20">
                  <label class="dropdown-title"><?= __('Таны хүүхдийн нас, жин') ?></label><br>
                  <select id='age' class="uk-button-dropdown form-dropdown">
                          <?php foreach($ages as $age) : ?>
                            <option class="dropdown-item"><?= $age ?></option>
                          <?php endforeach;?>
                    </select>
                </div>
                  <div class="uk-width-1-5 margin-top-20">
                    <div class="vertical-line"></div>
                    <label class="dropdown-title"><?= __('Таны овог, нэр') ?></label><br>
                    <input class="form-input uk-input" type="text" placeholder="Нэр">
                  </div>
                  <div class="uk-width-1-5 margin-top-20">
                    <div class="vertical-line"></div>
                    <label class="dropdown-title"><?= __('Холбоо барих утас') ?></label><br>
                    <input class="form-input uk-input" type="text" placeholder="Утас">
                  </div>
                  <div class=" uk-width-3-10 uk-text-center" style="padding-left:62px;">
                      <a href="#send-request" data-uk-modal class="uk-button uk-button-large donate-btn uk-text-center">
                        <?= $this->Html->image('icons/donate-btn.png', ['class' => 'btn-request-logo uk-text-center']) ?>
                        <?= __('Хүсэлт илгээх')?>
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </div>
<?php else :?>
  <div class="header-white-bg " data-uk-sticky="{ top:0, animation: 'uk-animation-slide-top'}">
      <div class="uk-container uk-container-center container-margin">
          <div class="uk-clearfix">
              <div class="uk-float-left margin-bottom-25">
                  <a href="/">
                      <?= $this->Html->image('logo.png', ['class' => 'logo-white-bg']) ?>
                  </a>
              </div>
              <a class="uk-visible-small uk-float-right" href="#showmobilemenu" data-uk-offcanvas style="margin-top: 19px"><i
                      class="uk-icon-navicon uk-icon-medium" style="margin-top: 9px; margin-right: 10px;"></i></a>
              <div class="uk-float-left uk-hidden-small menu-list">
                  <a href="<?= $this->Url->build('/home')?>"><?= __("Нүүр"); ?></a>
                  <a href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a>
                  <a href="<?= $this->Url->build('/news')?>"><?= __("Мэдээ") ?></a>
              </div>
              <div class="uk-float-right" style="margin-top:21px">
                  <a href="" class="uk-hidden-small">
                          <?= $this->Html->image('icons/phone-black.png', ['class' => 'btn-logo'])?>
                          <?= __("Холбогдох: ") . $contact;  ?></a>
                  <a arget="_blank" href="#send-donate" data-uk-modal class="uk-hidden-small uk-button uk-button-large btn-blue uk-text-center"><?= $this->Html->image('icons/present-white.png', ['class' => 'btn-logo'])?><?= __('Хандивлах')?>
                          </a>
              </div>
          </div>
          <div id="showmobilemenu" class="uk-offcanvas">
              <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
                  <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                      <li><a href="<?= $this->Url->build('/home')?>"><?= __("Нүүр")?></a></li>
                      <li><a href="<?= $this->Url->build('/about')?>"><?= __("Төслийн тухай")?></a></li>
                      <li><a href="<?= $this->Url->build('/news')?>"><?= __("Мэдээ")?></a></li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
<?php endif; ?>

<div id="send-request" class="uk-modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header text-bold request-title">Хамгаалалтын суудал авах хүсэлт 
           <a class="uk-modal-close uk-close uk-align-right"></a>
       </div>
        
        <?= $this->Form->create('', ['class' => 'uk-form-horizontal uk-margin-medium', 'id' => "request-carseat"]) ?>    
            <h3>Танд энэ өдрийн мэнд хүргэе.</h3>
            <p class="request-text">Хандиваар ирсэн суудлын тоо хязгаартай учир таны хүүхдийн нас, жинд таарсан суудал
                ирсэн тохиолдолд бид тантай эргүүлэн холбогдох юм. Уг маягтыг бөглөх нь таны хүүхэд хамгаалах суудал
                авна гэсэн баталгаа биш бөгөөд Монгол улсын замын хөдөлгөөний дүрмийн 23.8-нд заасны дагуу та өөрийн
                хүүхдээ тээвэрлэхдээ биеийн жин, өсөлтөд нь тохирсон зориулалтын нэмэгдэл суудал буюу хамгаалах хэрэгсэл
                ашиглах үүрэгтэйг анхаарна уу.</p>
            <div class="uk-form-row">
                <div class="uk-form-label ">Таны хүүхдийн нас:</div>
                <div class="uk-form-controls">
                  <?= $this->Form->input('age', ['class' => 'uk-width-1-1', 'options' => [__("0-2 нас") => __("0-2 нас"), __("2-6 нас") => __("2-6 нас"), __("6-10 нас") => __("6-10 нас"), __("10-с дээш") => __("10-с дээш")] ,'label' => false, 'id' => 'input-age']); ?>
                </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-label">Таны хүүхдийн жин:</div>
                <div class="uk-form-controls">
                <?= $this->Form->input('weight', ['class' => 'uk-width-1-1', 'options' => [__('0.1-10 кг') => __('0.1-10 кг'), __("10-13 кг") => __("10-13 кг"), __("14-18 кг") => __("14-18 кг"), __("19-25 кг") => __("19-25 кг"), __("26-35 кг") => __("26-35 кг")] ,'label' => false, 'id' => 'input-weight']); ?>
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="form-gender">Таны хүүхдийн хүйс:</label>
                <div class="uk-form-controls">
                <?= $this->Form->input('gender', ['class' => 'uk-width-1-1', 'options' => [__('Эрэгтэй') => __('Эрэгтэй'), __("Эмэгтэй") => __("Эмэгтэй")] ,'label' => false, 'id' => 'input-gender']); ?>
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="form-car-model">Таны машины загвар, модел:</label>
                <div class="uk-form-controls">
                    <?= $this->Form->input('car-model', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-car-model']); ?>
                </div>
            </div>

            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-fullname">Таны овог нэр:</label>
                <div class="uk-form-controls">
                 <?= $this->Form->input('fullname', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-fullname']); ?>
                </div>
            </div>

            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-phonenumber">Утасны дугаар:</label>
                <div class="uk-form-controls">
                    <?= $this->Form->input('phone', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-phone']); ?>  
                </div>
            </div>

            <div class="uk-form-row">
                <div class="uk-form-label">Гэрийн хаяг:</div>
                <div class="uk-form-controls">
                   <?= $this->Form->input('address', ['type' => 'textarea', 'rows' => '3', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-address']); ?>    
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-salary">Танай өрхийн сарын орлого хэд вэ?</label>
                <div class="uk-form-controls">
                   <?= $this->Form->input('salary', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-salary']); ?>    
                </div>
            </div>
            <div class="uk-form-row">
              <label class="uk-form-label" for="form-horizontal-child">Та хэдэн хүүхэдтэй вэ?</label> 
              <div class="uk-form-controls">
              <?= $this->Form->input('child-number', ['class' => 'uk-width-1-1', 'options' => [__("1") => __("1"), __("2") => __("2"), __("3") => __("3"), __("4-с дээш") => __("4-с дээш")] ,'label' => false, 'id' => 'input-child-number']); ?>
              </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-label">Хамгаалах суудал хэрэглэхгүй байсан шалтгаан юу вэ?</div>
                <div class="uk-form-controls">
                  <?= $this->Form->input('reason', ['type' => 'textarea', 'rows' => '5', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-reason']); ?>    
                </div>
            </div>
            <div class="uk-form-row">
              <label class="uk-form-label" for="form-signature">Гарын үсэг:</label>
              <div class="uk-form-controls">
                  <?= $this->Form->input('signature', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-signature']); ?>  
              </div>
              <p class="request-text">Хэрвээ “Амин хариуцлага” төслөөс хамгаалах суудал олгогдвол уг суудлыг зориулалтын
                дагуу ашиглаж, замын хөдөлгөөнд оролцохдоо хүүхдээ байнга хамгаална.</p>
            </div>
            <hr>
            <p><button class="uk-button uk-button-default uk-button-primary"
                    type="submit"><?= __('Хүсэлт илгээх')?></button></p>
          <?= $this->Form->end() ?>
    </div>
    
</div>


<div id="send-donate" class="uk-modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header text-bold request-title">Хамгаалалтын суудал хандивлах 
           <a class="uk-modal-close uk-close uk-align-right"></a></div>
        <?= $this->Form->create('', ['class' => 'uk-form-horizontal uk-margin-medium', 'id' => "donate-carseat"]) ?>    
            <h3>Танд энэ өдрийн мэнд хүргэе.</h3>
            <p class="request-text">Амин хариуцлага төсөлд суудал хандивлах гэж буйд баярлалаа. Бид таны холбогдох дугаараар холбогдон байгаа газраас нь суудлыг очиж авах боломжтой.</p>            
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-fullname">Таны нэр:</label>
                <div class="uk-form-controls">
                   <?= $this->Form->input('fullname', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-donate-fullname']); ?>
                </div>
            </div>

            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-phonenumber">Таны утасны дугаар:</label>
                <div class="uk-form-controls">
                    <?= $this->Form->input('phone', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-donate-phone']); ?>
                </div>
            </div>

            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-salary">Хамгаалалтын суудалыг хэдэн жил ашигласан вэ?</label>
                <div class="uk-form-controls">
                    <?= $this->Form->input('used-year', ['type' => 'text', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-used-year']); ?>
                </div>
            </div>

            <div class="uk-form-row">
                <label class="uk-form-label" for="form-horizontal-gender"><?= __('Хамгаалах суудал нь ослын үеэр ашиглагдаж байсан уу?')?></label>
                <div class="uk-form-controls">
                <?= $this->Form->input('is-used', ['class' => 'uk-width-1-1', 'options' => [__('Тийм') => __('Тийм'), __("Үгүй") => __("Үгүй")] ,'label' => false, 'id' => 'input-is-used']); ?>
                </div>
              </div>
            
            <div class="uk-form-row">
                <div class="uk-form-label" for="form-horizontal-type"><?= __("Хандивлах суудлын төрөл:")?></div>
                <div class="uk-form-controls">
                  <?= $this->Form->input('type', ['class' => 'uk-width-1-1', 'options' => [__('Нярай хүүхдийн') => __('Нярай хүүхдийн'), __("Бага насны хүүхдийн") => __("Бага насны хүүхдийн"), __("Өндөрлөгч суудал") => __("Өндөрлөгч суудал")] ,'label' => false, 'id' => 'input-donate-type']); ?>    
                </div>
            </div>

            <div class="uk-form-row">
                <div class="uk-form-label">Суудлыг очиж авах хаяг:</div>
                <div class="uk-form-controls">
                <?= $this->Form->input('address', ['type' => 'textarea', 'rows' => '3', 'class' =>'uk-input uk-width-1-1', 'label' => false, 'id' => 'input-donate-address']); ?>
              </div>
            </div>
            <p class="request-text"><?= __('Баярлалаа.')?></p>
            <p><button class="uk-button uk-button-default uk-button-primary"
                    type="submit"><?= __('Хандивлах')?></button></p>
        <?= $this->Form->end();?>
    </div>
</div>

<?= $this->Html->script("vue.min", ['block' => true]); ?>
<?php if (false) : ?>
  <script>
<?php else: ?>
  <?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>

var vm = new Vue({
    el: '#donate-app',
    data: {
      showDonate: true,
      },
    methods: {
      toggleDonate: function(show) {
        show = typeof show !== 'undefined' ? show : true;
        this.showDonate = show;
			},
			beforeMount(){
    		this.toggleDonate(true);
 			},
    }
  });

var scrollvm = new Vue({
  el: '#header-menu',
  methods: {
   handleScroll: function(evt, el) {
    if (window.scrollY > 200) {
      el.setAttribute("style", "hidden: false;")
    }
    return window.scrollY > 200;
    }
  }
});

$('#request-carseat').submit(function(event){
  event.preventDefault();
  
  var $form = $( this );
  var age = $('#input-age').val();
  var weight = $('#input-weight').val();
  var gender = $('#input-gender').val();
  var car_model = $('#input-car-model').val();
  var fullname = $('#input-fullname').val();
  var phone = $('#input-phone').val();
  var salary = $('#input-salary').val();
  var address = $('#input-address').val();
  var child_number = $('#input-child-number').val();
  var reason = $('#input-reason').val();
  var signature = $('#input-signature').val();

  $.ajax({
      url: "<?= $this->Url->build(["controller" => "Pages", "action" => "ajaxSendRequest"]) ?>",
      type: "POST",
      dataType: "json",
      beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');
      },
      data: {'age': age, 
             'weight': weight,
             'gender': gender,
             'car-model': car_model,
             'fullname' : fullname,
             'phone': phone,
             'address': address,
             'salary': salary,
             'child-number': child_number,
             'reason': reason,
             'signature': signature
            }
  }).success(function (data) {
    UIkit.modal.alert("Таны хүсэлт амжилттай илгээгдлээ.");
    UIkit.modal('#send-request', {modal: false}).hide();
  }).fail(function (data) {
    UIkit.modal.alert("Хүсэлт илгээхэд алдаа гарлаа. Та дахин оролдоно уу.");
  }).always(function() {
  });           
});

$('#donate-carseat').submit(function(event){
  event.preventDefault();
  
  var $form = $( this );
  var fullname = $('#input-donate-fullname').val();
  var phone = $('#input-donate-phone').val();
  var used_year = $('#input-used-year').val();
  var is_used = $('#input-is-used').val();
  var type = $('#input-donate-type').val();
  var address = $('#input-donate-address').val();

  $.ajax({
      url: "<?= $this->Url->build(["controller" => "Pages", "action" => "ajaxSendDonate"]) ?>",
      type: "POST",
      dataType: "json",
      beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->param('_csrfToken') ?>');
      },
      data: {'fullname': fullname, 
             'phone': phone,
             'used-year': used_year,
             'is-used': is_used,
             'type' : type,
             'address': address
            }
  }).success(function (data) {
    UIkit.modal('#send-donate', {modal: false}).hide();

    UIkit.modal.alert("Таны хүсэлт амжилттай илгээгдлээ. Хамгаалалтын суудал хандивласан таньд баярлалаа.");
  }).fail(function (data) {
    UIkit.modal.alert("Хүсэлт илгээхэд алдаа гарлаа. Та дахин оролдоно уу.");
  }).always(function() {
  });           
});
<?php if (false) : ?>
  </script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>