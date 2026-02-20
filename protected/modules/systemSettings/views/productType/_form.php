<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'product-type-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>





<?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textAreaGroup($model, 'description', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>

<?php
echo $form->switchGroup($model, 'has_children', array(
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


<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>
