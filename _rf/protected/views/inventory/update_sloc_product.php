<div class="row">
    <h5>
        <div class="col-xs-10 text-left"><b>POPIS - </b> <?= $sloc_has_product->sloc_code; ?></div>

        <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/inventory/slocContent/" . $sloc_has_product->sloc_id .'?tab=1'), array('class' => 'btn btn-primary btn-xs')); ?></div>

    </h5>
</div>


<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'product-form',
    'type'=> 'horizontal',
    'enableAjaxValidation'=>false,
)); ?>





<div class="row">
    <h4>
    <div class="col-xs-12 text-center"><b><?=$sloc_has_product->product->product_barcode;?></b></div>
    </h4>
    <p>
    <div class="col-xs-12 text-center"><?=$sloc_has_product->product->title;?></div>
    </p>
</div>
<hr>
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->textFieldGroup($model,'quantity',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'text-right skip','onfocus'=>'$(this).select();')),'labelOptions'=>array())); ?>
    </div>

</div>



<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small" id="submit-button"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {

        $('#SlocHasProductLog_quantity').select().focus();

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