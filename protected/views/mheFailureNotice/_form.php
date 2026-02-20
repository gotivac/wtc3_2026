<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'mhe-failure-notice-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





<?php echo $form->dropDownListGroup($model, 'mhe_location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(MheLocation::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>

	<?php echo $form->textAreaGroup($model,'description', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('rows'=>6)))); ?>

<?php
echo $form->switchGroup($model, 'operates', array(
        'widgetOptions' => array(
            'events' => array(
                'switchChange' => 'js:function(event, state) {
                        console.log(this); // DOM element
                        console.log(event); // jQuery event
                        console.log(state); // true | false
    }',
            ),
            'options' => array(
                'onText' => Yii::t('app', 'Yes'),
                'offText' => Yii::t('app', 'No'),
            )
        ),

    )
);
?>
<?php echo $form->dateTimePickerGroup($model, 'notice_datetime', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt pointer"></i>')); ?>
<?php echo $form->dateTimePickerGroup($model, 'solution_datetime', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt pointer"></i>')); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>

