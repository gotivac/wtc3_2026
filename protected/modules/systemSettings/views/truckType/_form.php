<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'truck-type-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





	<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

	<?php echo $form->textFieldGroup($model,'title_en',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->dropDownListGroup($model, 'gate_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(GateType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
<?php echo $form->numberFieldGroup($model,'parking_minutes',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>
<?php echo $form->numberFieldGroup($model,'dispatch_minutes',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>


<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>
