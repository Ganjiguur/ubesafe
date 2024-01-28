<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AssetCompress' => $baseDir . '/vendor/markstory/asset_compress/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'Batu' => $baseDir . '/plugins/Batu/',
        'CakeDC/Auth' => $baseDir . '/vendor/cakedc/auth/',
        'CakeDC/Users' => $baseDir . '/vendor/cakedc/users/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'MiniAsset' => $baseDir . '/vendor/markstory/mini-asset/',
        'PHPExcel' => $baseDir . '/plugins/PHPExcel/',
        'UikitTemplate' => $baseDir . '/plugins/UikitTemplate/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/'
    ]
];