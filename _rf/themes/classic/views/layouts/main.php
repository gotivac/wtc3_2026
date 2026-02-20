<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo Yii::app()->name; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Free yii themes, free web application theme">
        <meta name="author" content="otwd.com.au">
        <link href='https://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'>

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
            $cs->registerCssFile($baseUrl . '/css/classic.css');
            $cs->registerCssFile($baseUrl . '/plugins/bootstrap.select/css/bootstrap-select.css');
            $cs->registerScriptFile($baseUrl . '/js/shortcuts.js');
            $cs->registerScriptFile($baseUrl . '/plugins/jquery/jquery-ui.min.js');


            
        ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    </head>

    <body>

        <section id="navigation-main">
            <!-- Require the navigation -->
            <?php require_once 'tpl_navigation.php'?>
        </section><!-- /#navigation-main -->

        <section class="main-body">
            <div class="container" style="margin-top:100px;background-color:#fff;">

                <!-- Include content pages -->
                <?php echo $content; ?>
            </div>
        </section>

        <!-- Require the footer -->
        <?php require_once 'tpl_footer.php'?>
        <?php
        $cs->registerScriptFile($baseUrl.'/js/html2pdf/dist/html2pdf.bundle.min.js');
        ?>
        <script type="text/javascript" src="<?php echo $baseUrl . '/plugins/bootstrap.select/js/bootstrap-select.min.js'; ?>"></script>
        <script>
            $('.selectpicker').selectpicker({'liveSearch':true});
        </script>
    </body>
</html>