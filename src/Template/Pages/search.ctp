<?php
echo $this->Html->css("jquery.scrollbar", ['block' => 'top']);
echo $this->Html->script("jquery.scrollbar.min", ['block' => 'top']);

$total = 0; count($products) + count($articles) + count($pages);
$tabs = [];
$tabContes = [];

if(count($products)) {
   $total += count($products);
   $tabs[] = __("Бүтээгдэхүүн") . ' (' . count($products) . ')';
   $tabContes[] = $products;
}
if(count($articles)) {
   $total += count($articles);
   $tabs[] = __("Мэдээ, мэдээлэл") . ' (' . count($articles) . ')';
   $tabContes[] = $articles;
}
if(count($pages)) {
   $total += count($pages);
   $tabs[] = __("Бусад") . ' (' . count($pages) . ')';
   $tabContes[] = $pages;
}
global $searchText;
$searchText = $search_value;

function printContents($contents, $self) {
  global $searchText;
  
  foreach ($contents as $content) : ?>
    <a href="<?= $content['url'] ?>" target="_blank" class="search-result">
      <h4>
        <?php echo $self->Text->highlight($content['title'], $searchText, ['format' => '<span class="highlight">\1</span>']);  ?>
      </h4>
      <p>
        <?php echo $self->Text->highlight($content['body'], $searchText, ['format' => '<span class="highlight">\1</span>']);  ?>
      </p>
    </a>
  <?php endforeach;
}
?>

<style>
  .search-result {
    display: block;
    padding: 10px 0px 7px 0px;
    /*border-bottom: 1px solid #ebebeb;*/
  }
  .search-result h4 {
    font-size: 16px!important;
    font-weight: 500;
    margin-bottom: 3px;
  }
  .search-result:hover h4 {
    color: #b2aa00;
    /*text-decoration: underline;*/
  }
  
  .search-result p{
    color: #666!important;
    margin: 0px;
  }
  .highlight {
    font-weight: 700;
    background-color: rgba(255,255,0,0.3);
  }
  .uk-form-row>input.main {
    width: calc(100% - 90px);
  }
  .inner-nav>.scrollbar-macosx {
    max-width: 100%;
  }
  .inner-nav-container {
    overflow: hidden;
    padding: 0 28px;
    margin: 30px -28px -25px -28px;
    border-top: 1px solid #ebebeb;
  }
  #tab-contents {
    padding: 0px!important;
    border: none;
  }
</style>

<?= $this->element('site/crumb', ['emptyCrumb' => 'true', 'pageTitle' => __("Хайлт")]); ?>

<div class="maincontent paddingbottom-remove">
  <div class="uk-container uk-container-center">
    <div class="maincontent_detail">
      <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
        <div class="uk-width ck-pagecontent">
          <div class="maintext">
            <h3 class="page-title"><?= __("Хайлтын үр дүн") ?> (<?= $total ?>)</h3>
            <?= $this->Form->create('', ['type' => 'get', 'class'=>'', 'url' => ['plugin' => null, 'controller' => 'pages', 'action' => 'search']]) ?>
              <div class="uk-form-row" style="max-width:500px;">
                <input type="search" name="searchText" class="uk-form-large ck-input medium main" value="<?= isset($searchText) ? $searchText : '' ?>" style="width: calc(100% - 98px);">
                <button type="submit" class="grapebutton uk-button uk-button-large top uk-float-right"><i class="uk-icon-search"></i> <?= __("Хайх") ?></button>
              </div>
          <?php echo $this->Form->end(); ?>

          <div class="inner-nav-container">
            <div class="inner-nav">
              <div class="scrollbar-macosx" data-uk-switcher="{connect: '#tab-contents', animation:'fade', swiping:false}">
              <?php foreach ($tabs as $ind => $tab) : ?>
              <a href="" class="nav-item <?= ($ind == 0) ? 'active' : '' ?>"><?= $tab ?></a>
              <?php endforeach; ?>
              </div>
            </div>
          </div>
            
          <ul id="tab-contents" class="uk-switcher">
            <?php foreach ($tabContes as $contents) : ?>
              <li>
                <?= printContents($contents, $this) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="uk-width ck-sidebar">
          <?= $this->element('site/sidemenu', ['menuitems' => $sideMenus]) ?>
          
          <?= $this->element('site/side_links', ['show_calculators' => true]) ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->element('site/footer_top') ?>

<?php if (false) : ?>
<script>
<?php else: ?>
<?php $this->Html->scriptStart(['block' => true]); ?>
<?php endif; ?>    
  $(function() {
    innerNavScroll();
  });
<?php if (false) : ?>
</script>
<?php else: ?>
  <?php $this->Html->scriptEnd(); ?>
<?php endif; ?>