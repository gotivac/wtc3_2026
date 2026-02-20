<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'gate-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>





<?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker', 'onchange' => 'filterSections(this.value);')))); ?>

<?php // echo $form->dropDownListGroup($model, 'section_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Section::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
<div class="form-group">
    <label class=" col-sm-3 control-label"><?= Yii::t('app', 'Sections'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?= CHtml::dropDownList('GateHasSection[section_id]', $section_ids, CHtml::listData(Section::model()->findAll(), 'id', 'title'), array('class' => 'form-control selectpicker', 'multiple' => 'multiple')); ?>
    </div>
</div>
<?php echo $form->dropDownListGroup($model, 'gate_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(GateType::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<?php
echo $form->switchGroup($model, 'tms_gate', array(
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

<script>
    function filterSections(id) {
        $.ajax({
            url: '<?= Yii::app()->createUrl("systemSettings/default/ajaxGetSections");?>',
            type: 'post',
            data: {'location_id':id},
            success: function(data){
                $('#GateHasSection_section_id').html(data).selectpicker('refresh');
            }
        });
    }
</script>