

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'pick-form',
    'type'=> 'horizontal',
    'enableAjaxValidation'=>false,
)); ?>




<?php echo $form->textFieldGroup($model,'sscc_source',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->textFieldGroup($model,'product_barcode',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>

<?php echo $form->textFieldGroup($model,'quantity',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>



<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#PickWeb_sscc_source').focus().select();

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });

</script>