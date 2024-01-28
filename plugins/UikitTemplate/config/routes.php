<?php
use Cake\Routing\Router;

Router::plugin(
    'UikitTemplate',
    ['path' => '/uikit-template'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
