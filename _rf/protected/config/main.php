<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.


$ini_file = dirname(__FILE__) . '/../../settings/barcode.ini';
$barcode_settings = parse_ini_file($ini_file, true);

$base_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';

$wtc3models_path = $base_path.'/../../protected/models/';

Yii::setPathOfAlias('wtc3models', $wtc3models_path);



return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'WTC RF',
    'theme' => 'classic',
    'language' => 'sr_yu',
    // preloading 'log' component
    'preload' => array('log', 'booster'),
    // autoloading model and component classes
    'import' => array(
        'wtc3models.*',
        'application.models.*',
        'application.components.*',
        'application.extensions.yiimailer.YiiMailer',
        'application.extensions.barcode.*'
    ),
    'modules' => array(
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
    ),
    // application components
    'components' => array(
        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'booster' => array(
            'class' => 'ext.booster.components.Booster',
        ),
        'Helpers' => array(
            'class' => 'application.components.Helpers',
        ),
        'ImageTools' => array(
            'class' => 'application.components.ImageTools',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>/<size:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // database settings are configured in database.php
        'db' => require(dirname(__FILE__) . '/../../../protected/config/database.php'),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                  array(
                  'class'=>'CWebLogRoute',
                  ),
                 */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'barcode' => $barcode_settings,

    ),
    'behaviors' => array(
        // if not logged in, go to login page
        'class' => 'application.components.ApplicationBehavior',
    ),
);
