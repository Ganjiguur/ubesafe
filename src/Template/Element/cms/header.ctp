<style>
  .uk-form input:not([type]), .uk-form input[type=color], .uk-form input[type=date], .uk-form input[type=datetime-local], .uk-form input[type=email], .uk-form input[type=month], .uk-form input[type=number], .uk-form input[type=password], .uk-form input[type=search], .uk-form input[type=tel], .uk-form input[type=text], .uk-form input[type=time], .uk-form input[type=url], .uk-form input[type=week], .uk-form select, .uk-form textarea {
    padding: 3px;
  }
</style>
<?php 
    require_once(ROOT . DS . 'plugins' . DS . "tools.php");
?>

    <!-- main header -->
    <header id="header_main" class="back-gradient-cms">
        <div class="header_main_content">
            <nav class="uk-navbar">

                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>

                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>

                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                        <li>
                            <?= $this->Html->link('<i class="material-icons md-24 md-light">open_in_new</i>', ['controller'=>'Pages', 'action'=>'view', 'home'], ['class' => 'user_action_icon uk-visible-large', 'escape' => false, 'target'=>'_blank', 'title'=>__('Сайт руу очих'), 'data-uk-tooltip'=>"{pos:'bottom'}"]) ?>
                        </li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_image">
                                <?=$this->Html->image($currentUser->photo_url, ['alt'=>__('Зураг'), 'class'=>'md-user-image'])?>
                            </a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                    <li><?= $this->Html->link(__('Миний профайл'), ['plugin'=>null,'controller' => 'AppUsers', 'action' => 'edit'],['escape' => false]) ?></li>
                                    <li><?= $this->Html->link(__('Нууц үг солих'), ['plugin'=>null,'controller' => 'AppUsers', 'action' => 'changePassword'],['escape' => false]) ?></li>
                                    <li><?= $this->Html->link(__('Гарах'), ['plugin'=>null,'controller' => null, 'action' => 'logout'],['class'=>'text-12','escape' => false]) ?></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header><!-- main header end -->