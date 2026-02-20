<?php

$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
        ));
?>

<?php // echo $form->errorSummary($model); ?>

<?php echo $form->textFieldGroup($model, 'name', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
<?php echo $form->textFieldGroup($model, 'email', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php if ($model->isNewRecord): ?>
    <?php echo $form->textFieldGroup($model, 'password', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
<?php else: ?>
    <div class="form-group">
        <label class="col-sm-3 control-label required"><?php echo Yii::t('app', 'Change password'); ?> </label>
        <div class="col-md-6 col-sm-12 col-sm-9">
            <input type="text" id="new" name="Password[password]" class="form-control"/>
        </div>
    </div>
<?php endif; ?>

<?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>
<?php echo $form->dropDownListGroup($model, 'roles', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(AuthRole::model()->findAll(), 'lower_case', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>




<?php echo $form->textAreaGroup($model, 'notes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>


<?php echo $form->checkBoxListGroup($model, 'rf_access', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('data'=>array(
        'inbound'=>'INBOUND',
    'outbound'=>'OUTBOUND',
    'manipulate'=>'RELOKACIJA/PODELA',
    'web'=>'WEB','control'=>'KONTROLA',
    'info'=>'INFO',
    'inventory'=>'POPIS I ZAMENA SLOC'
),'htmlOptions' => array()))); ?>
<?php
echo $form->switchGroup($model, 'active', array(
    'widgetOptions' => array(
        'events' => array(
            'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
        ),
        'options' => array(
        'onText' => Yii::t('app','Yes'),
        'offText' => Yii::t('app','No'),
    )
    ),
    
        )
);
?>

<?php if (!$model->isNewRecord):?>

<?php
echo $form->switchGroup($model, 'global_client', array(
        'widgetOptions' => array(
            'events' => array(
                'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
            ),
            'options' => array(
                'onText' => Yii::t('app','Yes'),
                'offText' => Yii::t('app','No'),
            )
        ),

    )
);
?>
<?php endif; ?>

<div class="form-actions">
    <?php
    $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>


<?php
//   var_dump(Yii::app()->session['location']);
?>

<script>
    
    $('#User_roles').on('change',function(){
       var role = $(this).val();
       if (role == 'superadministrator') {
           $('#User_location_id').val('');
       }
    });
    $('#User_location_id').on('change',function(){
       var location = $(this).val();
       var role = $('#User_roles').val();
       if (location != '' && role == 'superadministrator') {
           $('#User_roles').val('administrator');
       }
    });
</script>    