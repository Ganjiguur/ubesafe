<?php

namespace App\Controller\Traits;

use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

trait MenuTrait {
  protected function setActiveMenusOfTree($url, $treeMenus) {
    foreach($treeMenus as $menu){
      if($menu->url == $url){
        $menu['selected'] = true;
        $_menu = clone $menu;
        unset($_menu->children);
        return [$treeMenus, [$_menu]];
      }
      if(!empty($menu->children)) {
        $result = $this->setActiveMenus($url, $menu->children);
        if ($result !== null) {
          $menu['selected'] = true;
          $menu->children = $result[0];
          $_menu = clone $menu;
          unset($_menu->children);
          array_unshift($result[1], $_menu);
          return [$treeMenus, $result[1]];
        }
      }
    }
    return null;
  }
  
  protected function setActiveMenusOfList($url, $menus, $setActive = true) {
    $parent_id = null;
    $crumblist = [];
    for ($i = count($menus) - 1; $i >= 0; $i--) {
      $menu = $menus[$i];
      if(($parent_id != null || $menu->url != $url) && ($menu->id !== $parent_id) || $menu->isOnlyOnFooter) {
        continue;
      }
      
      if($setActive) {
        $menu['selected'] = true;
        if(empty($crumblist)) {
          $menu['actually_selected'] = true;
        }
      }
      
      array_unshift($crumblist, $menu);
      if (isset($menu['parent_id'])) {
        $parent_id = $menu['parent_id'];
      } else {
        break;
      }
    }
    return [$menus, $crumblist];
  }
    
  protected function addToTree($tree, $menu, $lvl = 0, $maxLvl = 10) {
    $length = count($tree);
    $lastElement = $length > 0 ? $tree[$length - 1] : null;
    if($lastElement!= null && $lastElement['rght'] > $menu['rght']) {
      if ($lvl++ < $maxLvl) {
        $children = isset($lastElement['children']) ? $lastElement['children'] : [];
        $lastElement['children'] = $this->addToTree($children, $menu, $lvl, $maxLvl);
      }
    } else {
      $tree[] = $menu;
    }
    return $tree;
  }

  protected function filterAndMakeTree($menus, $field, $maxLvl = 10) {
    $tree = [];
    for ($i = 0; $i < count($menus); $i++) {
      $menu = $menus[$i];
      if(isset($menu[$field]) && $menu[$field] == true) {
        $_menu = clone $menu;
        $tree = $this->addToTree($tree, $_menu, 0, $maxLvl);
      }
    }
    return $tree;
  }
  
  protected function makeSideMenu($menus, $crumblist) {
    if(empty($crumblist)) {
      return null;
    }
    $parent = $crumblist[0];
    $sidemenus = [];
    for ($i = 0; $i < count($menus); $i++) {
      $menu = $menus[$i];
      if($parent['lft'] < $menu['lft'] && $parent['rght'] > $menu['rght']) {
        $sidemenus = $this->addToTree($sidemenus, $menu);
      }
    }
//    $parent['children'] = $sidemenus;
    return $sidemenus;
  }
  
  protected function setMenu() {
    $lang = $this->language ? $this->language : 'mn';
    $currentFullUrl = Router::url('/'.$this->request->url, true);
    if(isset($this->viewVars['category'])) {
      $articleCategory = $this->viewVars['category'];
      $currentFullUrl = Router::url('/page/' . $articleCategory->slug, true);
    }
    
    if(!empty($this->request->query("pageslug"))) {
      $currentFullUrl = Router::url('/page/' . $this->request->query("pageslug"), true);
    }
    
    $menusTable = TableRegistry::get('menus');
    $allMenus = $menusTable->find('all')
              ->where(['language' => $lang, 'active'=>true])
              ->order(['lft' => 'ASC'])
              ->toArray();
    
    if(empty($allMenus)) {
      $allMenus = $menusTable->find('all', ['site' => 'personal'])
              ->where(['language' => $lang, 'active'=>true])
              ->order(['lft' => 'ASC'])
              ->toArray();
    }

    $result = $this->setActiveMenusOfList($currentFullUrl, $allMenus);
    if(empty($result[1])) {
      $setCrumbEmpty = true;
      $tmpUrl = Router::url('/page/about', true);
      $result = $this->setActiveMenusOfList($tmpUrl, $allMenus, false);
    } else {
      $setCrumbEmpty = false;
    }
    $allMenus = $result[0];
    $crumblist = $result[1];

    $mainMenus = $this->filterAndMakeTree($allMenus, 'show_on_mainmenu', 7);
    $mobileMenus = $this->filterAndMakeTree($allMenus, 'show_on_mobile', 7);
    $footerMenus = $this->filterAndMakeTree($allMenus, 'show_on_footer', 7);

    $sideMenus = $this->makeSideMenu($allMenus, $crumblist);
    if($setCrumbEmpty) {
      $crumblist = count($crumblist) ? [$crumblist[0]] : [];
    }
    
    $this->set(compact('mainMenus', 'mobileMenus', 'footerMenus', 'crumblist', 'sideMenus'));
  }
  
  protected function setMenuBanners() {
    $Banners = TableRegistry::get('Banners');
    $this->set('menu_banners', $Banners->find('byType', ['type' => 'menu_banner', 'lang' => $this->language]));
  }
}
