<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'section-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>





<?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->numberFieldGroup($model, 'surface', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('step' => '0.01', 'placeholder' => '0.00')), 'append' => 'm2')); ?>


<div class="form-group">
    <label class="control-label col-sm-3"><?= Yii::t('app', 'TSM Start Time'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?php $this->widget('booster.widgets.TbTimePicker', array('name' => 'Section[tsm_start_time]', 'value' => $model->tsm_start_time, 'options' => array('showMeridian' => false,'defaultTime'=>false), 'htmlOptions' => array('class' => 'form-control'))); ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3"><?= Yii::t('app', 'TSM End Time'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?php $this->widget('booster.widgets.TbTimePicker', array('name' => 'Section[tsm_end_time]', 'value' => $model->tsm_end_time, 'options' => array('showMeridian' => false,'defaultTime'=>false), 'htmlOptions' => array('class' => 'form-control'))); ?>
    </div>


</div>


<?php
echo $form->switchGroup($model, 'wtc_managed', array(
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
<?php
echo $form->switchGroup($model, 'is_customs', array(
        'widgetOptions' => array(
            'events' => array(
                'switchChange' => 'js:function(event, state) {
							if (state){
							console.log(state);
							$("#customs_data").show();
							} else {
							$("#Section_customs_warehouse_number").val("");
							$("#Section_customs_warehouse_type").val("");
							$("#Section_customs_office_code").val("");
							$("#customs_data").hide();
							}
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
<div id="customs_data"<?= $model->is_customs ? '' : ' style="display:none"'; ?>>
    <?php echo $form->textFieldGroup($model, 'customs_warehouse_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
    <?php echo $form->textFieldGroup($model, 'customs_office_code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
    <?php echo $form->textFieldGroup($model, 'customs_warehouse_type', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>


<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $('#Section_is_customs').on('switch-change', function () {
        let is_customs = $(this).is(':checked');
        if (is_customs) {
            $('#customs-data').show();
        }
    });
</script>