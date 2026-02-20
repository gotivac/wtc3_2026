<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo Yii::app()->name; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Free yii themes, free web application theme">
        <meta name="author" content="otwd.com.au">
        <!-- <link href='https://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'> -->

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <?php
            $baseUrl = Yii::app()->theme->baseUrl;
            $cs = Yii::app()->getClientScript();
            Yii::app()->clientScript->registerCoreScript('jquery');
        ?>

        <!-- Fav and Touch and touch icons -->
        <link rel="shortcut icon" href="<?php echo $baseUrl;?>/img/icons/favicon.ico">
        
        <?php
            $cs->registerCssFile($baseUrl . '/css/classic_scanner.css');
            // $cs->registerCssFile($baseUrl . '/js/chosen/chosen.css');
            // $cs->registerScriptFile($baseUrl . '/js/jquery-ui-1.10.4.custom.min.js');
            // $cs->registerScriptFile($baseUrl . '/js/waiting.js');
            // $cs->registerScriptFile($baseUrl . '/js/chosen/chosen.jquery.js');
            // $cs->registerScriptFile($baseUrl . '/js/jquery.inputmask.js');
        ?>

    </head>

    <body>

       

        <section class="main-body">
            <div class="container" style="background-color:#efe;">

                <!-- Include content pages -->
                <?php echo $content; ?>
                
                
            </div>
        </section>

        <!-- Require the footer -->
        <?php require_once 'tpl_footer_s.php'?>
        
    </body>
</html>
