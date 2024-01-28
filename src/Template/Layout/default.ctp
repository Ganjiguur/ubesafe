<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
$isLoggedIn = $this->AppUser->isLoggedIn();
?>
<!DOCTYPE html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="description" content="<?= $this->AppView->getSiteMeta('site-description') ?>" />

    <meta property="og:title" content="<?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page) . ' | ' . $this->AppView->getSiteMeta('site-domain') : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:url" content="<?= $this->AppView->getSiteMeta('site-url', false) ?>" />
    <meta property="og:image" content="<?= (isset($og_image)) ? $og_image : $this->AppView->getSiteMeta('site-social-share', false) ?>" />
    <meta property="og:site_name" content="<?= (isset($og_site_name)) ? $og_site_name : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:description" content="<?= (isset($og_description)) ? $og_description : $this->AppView->getSiteMeta('site-description') ?>" />

    <?= $this->Html->meta('favicon.png', 'img/favicon.png', array('type' => 'icon', 'rel' => 'shortcut icon')); ?>

    <title>
      <?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page) : $this->AppView->getSiteMeta('site-title') ?>
    </title>



    <?= $this->Html->css('/froala_editor/froala_style.min.css'); ?>
    <?= $this->Html->css('/cms/css/uikit.almost-flat.min') ?>
    <?= $this->Html->css('/cms/css/components/tooltip.almost-flat.min') ?>
    <?= $this->Html->css('/cms/icons/flags/flags.min') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('/cms/css/main.min') ?>
    <?= $this->Html->css('/cms/css/styles_backend') ?>
    <?= $this->Html->css('/cms/css/shared') ?>
    <?= $this->Html->css('/cms/css/print') ?>


    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="/cms/js/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="/cms/js/matchMedia/matchMedia.addListener.js"></script>
        <link rel="stylesheet" href="/cms/css/ie.css" media="all">
    <![endif]-->

    <?php
    if ($this->AppView->getSiteMeta('debug', false)) {
      echo $this->Html->script('../cms/js/common');
    }
    ?>

    <style id="antiClickjack">body{display:none !important;}</style>
    
    <style>
      .hover-to-see {
        display: none;
      }

      .uk-nestable-panel:hover .hover-to-see {
        display: inline-block;
      }
    </style>
  </head>
  <body class="sidebar_main_open sidebar_main_swipe">
    <?= $this->element("cms/header"); ?>
    <?= $this->element("cms/sidemenu"); ?>

    <?= $this->fetch('content') ?>

    <?php
    if (!$this->AppView->getSiteMeta('debug', false)) {
      echo $this->Html->script('/cms/js/common.min');
    }
    ?>

<?php
echo $this->Html->script([
    '/cms/js/uikit_custom.min', 
    '/cms/js/altair_admin_common.min', 
    '/cms/js/idle_timer.min',
    '/cms/js/back_end',
    ]);
echo $this->fetch('script');
?>

    <script type="text/javascript">
      $(function () {
        if (Modernizr.touch) {
          // fastClick (touch devices)
          FastClick.attach(document.body);
        }
      });
      
      $window.load(function () {
        // ie fixes
        altair_helpers.ie_fix();
      });

      if (self === top) {
        var antiClickjack = document.getElementById("antiClickjack");
        antiClickjack.parentNode.removeChild(antiClickjack);
      } else {
        top.location = self.location;
      }

    </script>
    <?= $this->element('cms/idle_timeout') ?>

  </body>
</html>