<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'activity-type-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





	<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->dropDownListGroup($model, 'direction', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => array('in' => 'In', 'out'=>'Out'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>


<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>
