<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'pick-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>




<?php echo $form->textFieldGroup($model, 'sscc_source', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>



<?php echo $form->textFieldGroup($model, 'sscc_destination', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

<?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
<div class="row">
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right skip', 'onfocus' => '$(this).select();')), 'labelOptions' => array())); ?>
    </div>
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'packages', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right skip', 'onfocus' => '$(this).select();')), 'labelOptions' => array())); ?>
    </div>
    <div class="col-xs-4">
        <?php echo $form->textFieldGroup($model, 'units', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right', 'onfocus' => '$(this).select();')), 'labelOptions' => array())); ?>
    </div>
</div>


<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small" id="submit-button"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#Pick_sloc_code').focus().select();

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });

    $('#Pick_quantity').on('change', function () {
        let product_barcode = $('#Pick_product_barcode').val();
        let quantity = $(this).val();
        $.ajax({
            url: '<?= Yii::app()->createUrl("/activityPalettHasProduct/ajaxGetProduct");?>',
            type: 'post',
            dataType: 'json',
            data: {'product_barcode': product_barcode, 'quantity': quantity},
            success: function (data) {
                if (typeof data.packages != "undefined") {
                    $.each(data, function (key, val) {
                        $("#Pick_" + key + "_em_").remove();
                        $("#Pick_" + key).parent().removeClass("has-error");
                        $('#Pick_packages').val(data.packages);
                        $('#Pick_units').val(data.units);
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

<script>
    $('#pick-form').on('submit',function(){
        $('#submit-button').attr('disabled','disabled');
    });
</script>