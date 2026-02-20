<!doctype html>
<html lang="sr-SR">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title><?php echo Yii::app()->name; ?></title>
    <meta name="description" content=""/>
    <meta name="Author" content="OTWD"/>


    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext"
          rel="stylesheet" type="text/css"/>

    <!-- CORE CSS -->

    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');


   $cs->registerCssFile($baseUrl . '/css/essentials.css');
   $cs->registerCssFile($baseUrl . '/css/layout.css');
//    $cs->registerCssFile($baseUrl . '/plugins/dropzone/css/basic.css');
//    $cs->registerCssFile($baseUrl . '/plugins/nestable/nestable.css');
   $cs->registerCssFile($baseUrl . '/plugins/bootstrap.select/css/bootstrap-select.min.css');
    $cs->registerCssFile($baseUrl . '/css/otwd.css');


    ?>
    <link rel="icon" href="<?php echo $baseUrl; ?>/img/favicon.ico" type="image/x-icon">


      <script type="text/javascript" src="<?php echo $baseUrl . '/plugins/jquery/jquery-ui.min.js'; ?>"></script>

</head>
<!--
        .boxed = boxed version
-->
<body>


<?php echo $content; ?>

<!-- JAVASCRIPT FILES -->
<?php
Yii::app()->clientScript->registerScript(
    'myHideEffect', '$(".alert-success").animate({opacity: 1.0}, 2000).fadeOut("slow");', CClientScript::POS_READY
);

$cs->registerCssFile($baseUrl . '/js/chosen/chosen.css');
$cs->registerScriptFile($baseUrl . '/js/chosen/chosen.jquery.js');
$cs->registerScriptFile($baseUrl . '/js/shortcuts.js');
?>

<script type="text/javascript">var plugin_path = "<?php echo $baseUrl . '/plugins/'; ?>";</script>

<script type="text/javascript" src="<?php echo $baseUrl . '/js/app.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $baseUrl . '/plugins/bootstrap.select/js/bootstrap-select.min.js'; ?>"></script>
<!--
        <script type="text/javascript" src="<?php echo $baseUrl . '/plugins/dropzone/dropzone.js'; ?>"></script>

        <script type="text/javascript" src="<?php echo $baseUrl . '/plugins/nestable/jquery.nestable.js'; ?>"></script>



        <script type="text/javascript" src="<?php echo $baseUrl . '/js/menu.js'; ?>"></script>
-->
<script type="text/javascript" src="<?php echo $baseUrl . '/plugins/bootstrap.select/js/i18n/defaults-sr_SP.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $baseUrl . '/plugins/jquery/ui/i18n/datepicker-sr-SR.js'; ?>"></script>

<?php
$cs->registerScriptFile($baseUrl . '/js/html2pdf/dist/html2pdf.bundle.min.js');
?>
<script>
    $('.selectpicker').selectpicker({liveSearch : true, language: 'SP'});

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });

    $('.auto-dt').on('click', function () {
        var dt = new Date($.now());
        var dtf = dt.getDate() + "." + (dt.getMonth() + 1) + "." + dt.getFullYear() + " " + addZero(dt.getHours()) + ":" + addZero(dt.getMinutes());
        $(this).parent().prev().val(dtf);
    });

    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

</script>
</body>
</html>