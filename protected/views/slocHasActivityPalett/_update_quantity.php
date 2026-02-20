<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'activity-palett-has-product-form',
    'type' => 'vertical',
    'enableAjaxValidation' => false,
)); ?>

<?php echo $form->hiddenField($activity_palett_has_product_log, 'activity_palett_id',); ?>
<?php echo $form->hiddenField($activity_palett_has_product_log, 'activity_palett_has_product_id',); ?>
<?php echo $form->hiddenField($activity_palett_has_product_log, 'product_id',); ?>

<?php echo $form->textFieldGroup($activity_palett_has_product_log, 'sscc', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))); ?>
<?php echo $form->textFieldGroup($activity_palett_has_product_log, 'product_info', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly')))); ?>

<div class="row">
    <div class="col-md-4">
        <?php echo $form->textFieldGroup($activity_palett_has_product_log, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right')))); ?>
    </div>
    <div class="col-md-4">
        <?php echo $form->textFieldGroup($activity_palett_has_product_log, 'packages', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right')))); ?>
    </div>
    <div class="col-md-4">
        <?php echo $form->textFieldGroup($activity_palett_has_product_log, 'units', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right')))); ?>
    </div>
</div>

<?php echo $form->textAreaGroup($activity_palett_has_product_log, 'reason', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => '4')))); ?>
<div class="form-actions">
    <?php
    echo CHtml::ajaxSubmitButton(Yii::t('app', 'Save'), '', array(
        'dataType' => 'json',
        'type' => 'post',
        'success' => 'function(data) {

                        $(".error").parent().removeClass("has-error");
                     
                        
                        if (typeof data.id != "undefined") {
                            $.each(data, function(key,val) {
                                $("#ActivityPalettHasProductLog_"+key+"_em_").remove();
                                $("#ActivityPalettHasProductLog_"+key).parent().removeClass("has-error");
                            });
                            location.href=location.protocol + "//" + location.host + location.pathname;
                        }  else {
                            $.each(data, function(key,val) {
                            if (!$("#"+key+"_em_").is(":visible")) {
                                $("#"+key).after("<div id=\""+key+"_em_"+"\" class=\"help-block error\">"+val+"</div>");
                                $("#"+key).parent().addClass("has-error");
                                
                                }
                            }); 
                        } 
                    }',
    ), array('class' => 'btn btn-primary'));
    ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $('#ActivityPalettHasProductLog_quantity').on('change', function () {
        let quantity = $(this).val();
        let product_id = $('#ActivityPalettHasProductLog_product_id').val();
        $.ajax({
            url: '<?= Yii::app()->createUrl("/slocHasActivityPalett/ajaxArrangeProduct");?>',
            data: {'quantity' : quantity,'product_id':product_id},
            dataType: 'json',
            type: 'post',
            success: function(data) {
                if (typeof data.packages != "undefined" && typeof data.units != "undefined") {
                    $('#ActivityPalettHasProductLog_packages').val(data.packages);
                    $('#ActivityPalettHasProductLog_units').val(data.units);
                }
            }

        });
    })
</script>
