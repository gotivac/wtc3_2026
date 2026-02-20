<div class="row">
    <h5>
        <div class="col-xs-10 text-left"><b>POPIS - </b> <?= $sloc->sloc_code; ?></div>

        <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/inventory/slocContent/" . $sloc->id . '?tab=1'), array('class' => 'btn btn-primary btn-xs')); ?></div>

    </h5>
</div>
<div class="col-xs-12" >
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'product-form',
        'type' => 'horizontal',
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
    <?php echo $form->textFieldGroup($model, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>


    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-small" id="submit-button"><i class="glyphicon glyphicon-ok"></i></button>
    </div>

    <?php $this->endWidget(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#SlocHasProduct_product_barcode').focus();




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