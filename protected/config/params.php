<?php

$ini_file = dirname(__FILE__) . '/../../settings/barcode.ini';
$barcode_settings = parse_ini_file($ini_file, true);

return array(

    'barcode' => $barcode_settings,
    'adminEmail' => 'gotivac@gmail.com',
    'adminDelete' => 'false',

    'RestfullYii' => array(
        'post.filter.req.auth.ajax.user' => function(){
            return true;
        },

    ),




);
