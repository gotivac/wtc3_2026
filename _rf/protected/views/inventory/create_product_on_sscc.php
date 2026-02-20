<div class="row">
    <h5>
        <div class="col-xs-10 text-left"><b>POPIS - </b> <?= $activity_palett->sscc; ?></div>

        <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/inventory/viewPalett/" . $activity_palett->id), array('class' => 'btn btn-primary btn-xs')); ?></div>

    </h5>
    <h5><div class="col-xs-12">Dodaj proizvod</div></h5>
</div>
<div class="col-xs-12" style="margin-top: 0px !important">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'product-form',
        'type' => 'horizontal',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>

    <?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
    <?php echo $form->textFieldGroup($model, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

    <div class="form-group">
        <div class="col-xs-6">
            <?= CHtml::textField('ActivityPalettHasProduct[packages]', $model->packages, array('class' => 'form-control', 'placeholder' => 'Paketa')); ?>
        </div>
        <div class="col-xs-6">
            <?= CHtml::textField('ActivityPalettHasProduct[units]', $model->units, array('class' => 'form-control', 'placeholder' => 'Van paketa')); ?>
        </div>
    </div>


    <div class="form-group">

        <?php if ($model->activityPalett->activityOrder->client->hasPickMethod('FEFO')): ?>

            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'DD')); ?></div>
            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'MM')); ?></div>
            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'YYYY')); ?></div>
        <?php endif; ?>

    </div>
    <?php if ($model->activityPalett->activityOrder->client->hasPickMethod('BATCH')): ?>
        <?php echo $form->textFieldGroup($model, 'batch', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
    <?php endif; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-small" id="submit-button"><i class="glyphicon glyphicon-ok"></i></button>
    </div>

    <?php $this->endWidget(); ?>
</div>



<script>
    $(document).ready(function () {
        $('#ActivityPalettHasProduct_product_barcode').focus();




        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

        $('#ActivityPalettHasProduct_quantity').on('change', function () {
            let product_barcode = $('#ActivityPalettHasProduct_product_barcode').val();
            let quantity = $(this).val();
            $.ajax({
                url: '<?= Yii::app()->createUrl("/activityPalettHasProduct/ajaxGetProduct");?>',
                type: 'post',
                dataType: 'json',
                data: {'product_barcode': product_barcode, 'quantity': quantity},
                success: function (data) {
                    if (typeof data.packages != "undefined") {
                        $.each(data, function (key, val) {
                            $("#ActivityPalettHasProduct_" + key + "_em_").remove();
                            $("#ActivityPalettHasProduct_" + key).parent().removeClass("has-error");
                            $('#ActivityPalettHasProduct_packages').val(data.packages);
                            $('#ActivityPalettHasProduct_units').val(data.units);
                        });

                    } else {
                        $.each(data, function (key, val) {
                            if (!$("#" + key + "_em_").is(":visible")) {
                                $("#" + key).after("<div id=\"" + key + "_em_" + "\" class=\"help-block error\">" + val + "</div>");
                                $("#" + key).parent().addClass("has-error");
                            }
                        });

                        $('#ActivityPalettHasProduct_quantity').val('');
                        $('#ActivityPalettHasProduct_product_barcode').focus().select();

                    }
                }
            });
        });


    });
</script>