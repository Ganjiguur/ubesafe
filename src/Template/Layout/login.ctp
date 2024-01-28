<?php require_once(ROOT . DS . 'plugins' . DS . "tools.php");?>

<!DOCTYPE html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="description" content="<?= $this->AppView->getSiteMeta('site-description') ?>" />
    <meta name="HandheldFriendly" content="true">

    <meta property="og:title" content="<?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page) . ' | '. $this->AppView->getSiteMeta('site-domain') : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:url" content="<?= $this->AppView->getSiteMeta('site-url', false) ?>" />
    <meta property="og:image" content="<?= (isset($og_image)) ? $og_image : $this->AppView->getSiteMeta('site-social-share', false) ?>" />
    <meta property="og:site_name" content="<?= (isset($og_site_name)) ? $og_site_name : $this->AppView->getSiteMeta('site-title') ?>" />
    <meta property="og:description" content="<?= (isset($og_description)) ? $og_description : $this->AppView->getSiteMeta('site-description') ?>" />

    <?= $this->Html->meta('favicon.png', 'img/favicon.png', array('type' => 'icon', 'rel' => 'shortcut icon')); ?>

    <title>
        <?= (isset($title_for_page)) ? str_replace('"', "'", $title_for_page) : $this->AppView->getSiteMeta('site-title') ?> - Login Page
    </title>

    <?php echo $this->Html->css('/cms/css/uikit.almost-flat.min')?>
    <?= $this->Html->css('/cms/css/login_page.min')?>
    <style id="antiClickjack">body{display:none !important;}</style>
</head>
<body class="login_page">
    <div class="login_page_wrapper">
        <div class="md-card" id="login_card">
            <?= $this->fetch('content') ?>
        </div>
    </div>   
    <?php 
        echo $this->Html->script(['/cms/js/common.min', '/cms/js/uikit_custom.min', '/cms/js/altair_admin_common.min', '/cms/js/login.min']);
        echo $this->fetch('script');
    ?>
    
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }

    </script>
</body>
</html>

