<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'time-slot-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
)); ?>


<?php $activity_types = $this->user->getAllowedActivityTypes(2); ?>


<?php if (count($activity_types) == 0) throw new CHttpException(403, Yii::t('app', 'You are not authorized to perform this action.')); ?>

<?php if (strpos(Yii::app()->user->roles, 'administrator')): ?>
    <?php
    echo $form->switchGroup($model, 'urgent', array(
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
<?php endif; ?>

<?php if (count($activity_types) > 1): ?>
    <?php echo $form->dropDownListGroup($model, 'activity_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData($activity_types, 'id', 'title'), 'htmlOptions' => array('empty' => '')))); ?>
<?php else: ?>
    <?php echo $form->hiddenField($model, 'activity_type_id', array('value' => $activity_types[0]->id)); ?>
<?php endif; ?>

<?php echo $form->dropDownListGroup($model, 'truck_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(TruckType::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '')))); ?>

<?php echo $form->textFieldGroup($model, 'license_plate', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<div class="form-actions">
    <?php
    $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'success',
        'label' => $model->isNewRecord ? Yii::t('app', 'Clients') : Yii::t('app', 'Save'),
        'htmlOptions' => array('name' => '_form_button')
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
