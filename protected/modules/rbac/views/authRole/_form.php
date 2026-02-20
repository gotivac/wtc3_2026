
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'auth-role-form',
        'type'=> 'vertical',
	'enableAjaxValidation'=>false,
)); ?>





	<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-md-12 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

	<?php echo $form->textFieldGroup($model,'lower_case',array('wrapperHtmlOptions' => array('class' => 'col-md-12 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>$model->isNewRecord ? array() : array('readonly'=>'readonly') ))); ?>


<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $('#AuthRole_title').on('blur',function(){
        let title = $(this).val().toLowerCase().replace(/\s/g, '_');
       $('#AuthRole_lower_case').val(title);
    });
</script>