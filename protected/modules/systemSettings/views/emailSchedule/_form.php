<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'email-schedule-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





	<?php echo $form->textFieldGroup($model,'title',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255,'readonly'=>'readonly')))); ?>
	<?php echo $form->textFieldGroup($model,'command',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255,'readonly'=>'readonly')))); ?>

	<?php echo $form->textFieldGroup($model,'action',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255,'readonly'=>'readonly')))); ?>

	<?php echo $form->textAreaGroup($model,'recipients', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('rows'=>6)))); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>
