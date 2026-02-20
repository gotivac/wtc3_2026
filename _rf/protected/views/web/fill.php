<h5>
    <div class="text-center col-xs-12">
        WEB DOPUNA
    </div>

</h5>
<div class="clearfix"></div>

<?php
$this->widget('booster.widgets.TbAlert', array(
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array(),
    'userComponentId' => 'user',
    'alerts' => array( // configurations per alert type
        // success, info, warning, error or danger
        'success' => array('closeText' => '&times;'),
        'info', // you don't need to specify full config
        'warning' => array('closeText' => false),
        'error' => array('closeText' => Yii::t('app','Error')),
    ),
));
?>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'pick-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>




<?php echo $form->textFieldGroup($model, 'sscc_source', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>



<?php echo $form->textFieldGroup($model, 'sloc_destination', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

<?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
<div class="row">
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right skip', 'onfocus' => '$(this).select();')), 'labelOptions' => array('label' => false))); ?>
    </div>
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'packages', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right skip', 'onfocus' => '$(this).select();')), 'labelOptions' => array('label' => false))); ?>
    </div>
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'units', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right', 'onfocus' => '$(this).select();')), 'labelOptions' => array('label' => false))); ?>
    </div>
</div>


<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#WebFill_sscc_source').focus().select();

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });

    $('#WebFill_quantity').on('change', function () {
        let product_barcode = $('#WebFill_product_barcode').val();
        let quantity = $(this).val();
        $.ajax({
            url: '<?= Yii::app()->createUrl("/activityPalettHasProduct/ajaxGetProduct");?>',
            type: 'post',
            dataType: 'json',
            data: {'product_barcode': product_barcode, 'quantity': quantity},
            success: function (data) {
                if (typeof data.packages != "undefined") {
                    $.each(data, function (key, val) {
                        $("#WebFill_" + key + "_em_").remove();
                        $("#WebFill_" + key).parent().removeClass("has-error");
                        $('#WebFill_packages').val(data.packages);
                        $('#WebFill_units').val(data.units);
                    });

                } else {
                    $.each(data, function (key, val) {
                        if (!$("#" + key + "_em_").is(":visible")) {
                            $("#" + key).after("<div id=\"" + key + "_em_" + "\" class=\"help-block error\">" + val + "</div>");
                            $("#" + key).parent().addClass("has-error");
                        }
                    });

                }
            }
        });
    });

</script>