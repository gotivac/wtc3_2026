<?php

return array(
    // uncomment the following to enable the Gii tool

    'gii' => array(
        'class' => 'system.gii.GiiModule',
        'password' => '0000',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        'ipFilters' => array('127.0.0.1', '::1'),
        'generatorPaths' => array(
            'ext.booster.gii'
        ),
    ),


    'rbac',
    'systemSettings',
    'ws',
    'log',






);
