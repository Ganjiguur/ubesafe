<?php 
$isDebug = true;//$this->AppView->getSiteMeta('debug');
$assetRaw = false;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>
<?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page). ' - '. $this->AppView->getSiteMeta('site-title') : $this->AppView->getSiteMeta('site-title') ?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?= $this->AppView->getSiteMeta('site-description') ?>" />
    <meta name="keywords" content="<?= $this->AppView->getSiteMeta('site-keywords', false) ?>" />
    <meta name="author" content="Batu Digital. www.batu.digital">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    
    <meta property="og:title" content="<?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page) . ' | '. $this->AppView->getSiteMeta('site-title') : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:image" content="<?= (isset($og_image)) ? $og_image : $this->AppView->getSiteMeta('site-social-share', false) ?>" />
    <meta property="og:site_name" content="<?= (isset($og_site_name)) ? $og_site_name : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:description" content="<?= (isset($og_description)) ? $og_description : $this->AppView->getSiteMeta('site-description') ?>" />

    <?= $this->Html->meta('favicon.png', 'img/favicon.png', array('type' => 'icon', 'rel' => 'shortcut icon', "sizes" => "50x50")); ?>
    
    <?php
    echo $this->AssetCompress->css('style.min.css', ['raw' => $assetRaw]);
    echo  $this->fetch('css'); 
    echo $this->AssetCompress->css('amin-hariutslaga.min.css', ['raw' => $assetRaw]);
    if ($isDebug) {
      echo $this->Html->script("jquery-2.1.4");
    }
    ?>
    
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
    <!--<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700&display=swap&subset=cyrillic-ext" rel="stylesheet">  
  </head>
  <body class="">
      <?= $this->element('site/header') ?>
      <?= $this->fetch("content") ?>
      <?= $this->element('site/footer') ?>
    
      <?= $this->AppView->getSiteMeta('site-analytics-script') ?>

      <?php 
      if (!$isDebug) {
        echo $this->Html->script("jquery-2.1.4");
      }
      echo $this->fetch('top');
      echo $this->AssetCompress->script('amin-hariutslaga.min.js', ['raw' => $assetRaw]);
      echo $this->fetch('script');
      ?>
       <script>
      $(function(){
        if($('.rellax').length && $(window).width() > 767) {
          new Rellax('.rellax', {speed: 2});
        }
        
        if($('.rellax-mobile').length && $(window).width() <= 767) {
          new Rellax('.rellax-mobile', {speed: 2});
        }
      });
    </script>
  </body>
</html>