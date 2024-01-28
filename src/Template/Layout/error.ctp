<?php
require_once(ROOT . DS . 'plugins' . DS . "tools.php");
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

    <?= $this->Html->meta('favicon.png', 'img/favicon.png', array('type' => 'icon', 'rel' => 'shortcut icon')); ?>

    <title>
        <?= 'Error | ' . $this->AppView->getSiteMeta('site-title') ?>
    </title>

    <!--<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,500,700&subset=cyrillic-ext,latin' rel='stylesheet' type='text/css'>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/css/uikit.almost-flat.min.css" />

    <?= $this->Html->css('/cms/css/error_page.min')?>
    <?= $this->fetch('css')?>
    <style id="antiClickjack">body{display:none !important;}</style>
</head>
<body class="error_page">

    <?= $this->fetch('content') ?>

    <?php 
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