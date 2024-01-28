<?php
  $mode = isset($mode) ? $mode : 'tree';
?>

<div class="sidemenu">
  <?php if($mode == 'tree'): ?>
  <?php if(!empty($crumblist)) : ?>
    <div class="title">
      <?= $crumblist[0]->name ?>
    </div>
  <?php endif; ?>  
  <div class="content">
    <ul class="uk-nav uk-nav-parent-icon sidemenu-nav" data-uk-nav>
      <?php foreach ($menuitems as $menuitem) : ?>
      <?php $hasChild = (isset($menuitem->children) && count($menuitem->children) > 0) ?>
      <li class="<?= $menuitem['selected'] == true ? 'uk-active' : '' ?> <?= $hasChild ? "uk-parent" : "" ?>">
        <a href="<?= $hasChild ? '#' : $menuitem->url ?>" class="text-grey"><?= strip_tags($menuitem->nameLocale) ?></a>
          <?php if($hasChild): ?>
            <ul class="uk-nav-sub">
              <?php foreach ($menuitem->children as $child) : ?>
              <?php $hasGrand = (isset($child->children) && count($child->children) > 0); ?>
              <li class="<?= $child['selected'] == true ? 'uk-active' : '' ?> <?= $hasGrand ? "uk-parent" : "" ?>">
                <a href="<?= $child->url ?>" class="text-grey">
                  <?= $child->nameLocale ?>
                </a>
                <?php if($hasGrand && $child['selected'] == true): ?>
                  <ul class="uk-nav-sub">
                    <?php foreach ($child->children as $grand) : ?>
                    <?php $hasGrand2 = (isset($grand->children) && count($grand->children) > 0); ?>
                    <li class="<?= $grand['selected'] == true ? 'uk-active' : '' ?>">
                      <a href="<?= $grand->url ?>" class="text-grey">
                        <?= $grand->nameLocale ?>
                      </a>
                      <?php if($hasGrand2 && $grand['selected'] == true): ?>
                        <ul class="uk-nav-sub">
                          <?php foreach ($grand->children as $grand2) : ?>
                          <?php $hasGrand3 = (isset($grand2->children) && count($grand2->children) > 0); ?>
                          <li class="<?= $grand2['selected'] == true ? 'uk-active' : '' ?>">
                            <a href="<?= $grand2->url ?>" class="text-grey">
                              <?= $grand2->nameLocale ?>
                            </a>
                            <?php if($hasGrand3 && $grand2['selected'] == true): ?>
                              <ul class="uk-nav-sub">
                                <?php foreach ($grand2->children as $grand3) : ?>
                                <li class="<?= $grand3['selected'] == true ? 'uk-active' : '' ?>">
                                  <a href="<?= $grand3->url ?>" class="text-grey">
                                    <?= $grand3->nameLocale ?>
                                  </a>
                                </li>
                                <?php endforeach; ?>
                              </ul>
                            <?php endif; ?>
                          </li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php elseif($mode == 'list') : ?>
    <?php if(!empty($title)) : ?>
      <div class="title">
        <i class="uk-icon-question-circle text-16"></i> <?= $title ?>
      </div>
    <?php endif; ?>
    

    <div class="content">
      <ul class="uk-nav uk-nav-parent-icon sidemenu-nav" data-uk-nav>
        <?php foreach ($items as $item) : ?>
          <li class="<?= $active == $item->id ? 'uk-active' : '' ?> ">
           <a href="<?= $item->url ?>" class="text-grey"><?= $item->titleLocale ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>