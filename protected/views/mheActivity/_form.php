<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'mhe-activity-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





<?php echo $form->dropDownListGroup($model, 'mhe_activity_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(MheActivityType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>

<?php echo $form->dropDownListGroup($model, 'mhe_location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(MheLocation::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>

<?php echo $form->dateTimePickerGroup($model, 'date_and_time', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt pointer"></i>')); ?>

	<?php echo $form->textAreaGroup($model,'notes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('rows'=>6)))); ?>


<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>
