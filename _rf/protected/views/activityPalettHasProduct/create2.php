<h4 class="text-center">
    <?= $model->activityPalett->activityOrder->order_number; ?>
</h4>


<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'activity-palett-has-product-form',
    'type' => 'vertical',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div class="col-xs-1" style="padding-top:10px;">
    <a style="color:red;" id="remove-palett"
       href="<?= Yii::app()->createUrl("/activityPalettHasProduct/deletePalett/" . $model->activity_palett_id); ?>"><i
                class="glyphicon glyphicon-remove-circle"></i></a>
</div>

<div class="col-xs-8 text-right">
    <?php echo $form->textFieldGroup($model, 'sscc', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('readonly' => 'readonly', 'style' => 'cursor:pointer;font-size:1em')), 'labelOptions' => array('label' => false))); ?>
</div>
<div class="col-xs-1 text-left">

    <a href="<?= Yii::app()->createUrl("/activityPalettHasProduct/create/" . $model->activityPalett->activity->id); ?>"
       class="btn btn-success btn-small"><i class="glyphicon glyphicon-list"></i></a>
</div>

<?php $this->endWidget(); ?>

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
    <?php echo $form->textFieldGroup($model, 'delivery_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

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
            <!--
	<div class="col-xs-4"><?= CHtml::dropDownList("ExpireDate[date]", '', Helpers::numberArray(1, 31), array('class' => 'form-control skip', 'empty' => '')); ?></div>
	<div class="col-xs-4"><?= CHtml::dropDownList("ExpireDate[month]", '', Helpers::numberArray(1, 12), array('class' => 'form-control', 'empty' => '')); ?></div>
	<div class="col-xs-4"><?= CHtml::dropDownList("ExpireDate[year]", '', Helpers::numberArray(date('Y'), date('Y') + 5), array('class' => 'form-control', 'empty' => '')); ?></div>
	-->
            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'DD')); ?></div>
            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'MM')); ?></div>
            <div class="col-xs-4"><?= CHtml::textField("ExpireDate[date]", '', array('class' => 'form-control skip', 'placeholder' => 'YYYY')); ?></div>
        <?php endif; ?>

    </div>
    <?php if ($model->activityPalett->activityOrder->client->hasPickMethod('BATCH')): ?>
        <?php echo $form->textFieldGroup($model, 'batch', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
    <?php endif; ?>
    <div class="form-actions"><!--
        <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-check"></i></button>
        -->
        <button type="submit" class="btn btn-primary">OK</button>

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
        $('#remove-palett').on('click', function (e) {
            if (!confirm("Da li ste sigurni?")) {
                e.preventDefault();
            }
        });

        $('#ActivityPalettHasProduct_sscc').on('click', function () {
            location.href = '<?=Yii::app()->createUrl("/activityPalettHasProduct/view/" . $model->activity_palett_id);?>';
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