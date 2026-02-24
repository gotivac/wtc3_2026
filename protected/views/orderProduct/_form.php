<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'order-product-form',
        'type'=> 'vertical',
	'enableAjaxValidation'=>false,
)); ?>



<div class="well">

	<?php echo $form->hiddenField($model,'order_client_id'); ?>

    <?php echo $form->dropDownListGroup($model, 'product_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData($products, 'id', 'internal_product_number'), 'htmlOptions' => array('empty' => '','onchange' => '$("#OrderProduct_quantity").val("");','class'=>'selectpicker')))); ?>

	<?php echo $form->textFieldGroup($model,'quantity',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'calc')))); ?>

	<?php echo $form->textFieldGroup($model,'paletts',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array()))); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Save'),
		)); ?>
</div>
</div>
<?php $this->endWidget(); ?>

<script>
    $(document).ready(function(){
        $('.calc').on('change',function(){
            let product_id = $('#OrderProduct_product_id').val();
            let quantity = $('#OrderProduct_quantity').val();
            $.ajax({
                url : '<?= Yii::app()->createUrl("orderProduct/ajaxCalcPaletts");?>',
                data: {'product_id':product_id,'quantity':quantity},
                type: 'post',
                dataType: 'json',
                success: function(data)
                {
                    if (data.result && data.result < 50) {
                        $('#OrderProduct_paletts').val(data.result);
                    }
                }
            });
        });
    });
</script>