<?php 
    require_once ROOT . DS . 'plugins' . DS . "tools.php";

    $isAdmin = $this->AppUser->isAdmin();
    $currentCtrl = strtolower($this->request->controller);
?>
    

<aside id="sidebar_main">
    <div class="sidebar_main_header">
        <div class="sidebar_logo" style="height:90px;padding-top:15px;">
          <a href="<?= $this->Url->build(['controller' => 'dashboard', 'action' => 'index']) ?>" class="sSidebar_hide sidebar_logo_large">
                <?=$this->Html->image('/cms/img/logo.png',['class'=>'logo_regular','alt'=>'logo', 'width' => 150 ])?>
            </a>
        </div>
    </div>
    <div class="menu_section">
        <ul>
            <?php foreach ($this->AppUser->getUserMenu() as $menuItem):?> 
                <?php 
                $menuTitle = '<span class="menu_icon"><i class="material-icons">'.$menuItem['icon'].'</i></span><span class="menu_title">'. $menuItem['title'] .'</span>';
                if($menuItem['action']['controller'] == 'FormRecords') {
                  $menuTitle .= ' ' . $this->cell('Extend::TotalRequests');
                }
                ?>
                <li class="<?php if($currentCtrl == strtolower($menuItem['action']['controller'])) echo 'current_section'?>" title="<?=$menuItem['title']?>">
                    <?= $this->Html->link($menuTitle, $menuItem['action'], ['escapeTitle'=>false]) ?>
                </li>
            <?php endforeach;?>
           
            <?php if ($isAdmin): ?>
                <li title="<?= __("Admin Menu") ?>">
                    <a href="#">
                        <span class="menu_icon"><i class="material-icons">&#xE241;</i></span>
                        <span class="menu_title"><?= __("Admin Menu") ?></span>
                    </a>
                    <ul>
                        <?php foreach (adminModules() as $menuItem):?> 
                        <li class="<?php if($currentCtrl == strtolower($menuItem['action']['controller'])) echo 'act_item'?>" title="<?=$menuItem['title']?>">
                            <?= $this->Html->link($menuItem['title'], $menuItem['action']) ?>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </li>
            <?php endif;?>
        </ul>
    </div>
</aside>

<!--TOOD add badge count to firm submits-->