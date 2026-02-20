<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'sloc-form',
        'type'=> 'horizontal',    
	'enableAjaxValidation'=>false,
)); ?>





<?php echo $form->dropDownListGroup($model, 'sloc_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(SlocType::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
<div class="form-group">
    <label class="control-label col-sm-3"><?= Yii::t('app', 'Location'); ?></label>
    <div class="col-md-6 col-sm-12">
        <?php echo CHtml::dropdownList('location', $model->section ? $model->section->location_id : '', CHtml::listData(Location::model()->findAll(), 'id', 'title'), array('class' => 'form-control selectpicker', 'empty' =>Yii::t('app','All Locations'), 'onchange' => 'filterSections(this.value);')); ?>
    </div>
</div>
<?php echo $form->dropDownListGroup($model, 'section_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Section::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

	<?php echo $form->textFieldGroup($model,'sloc_code',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

	<?php echo $form->textFieldGroup($model,'sloc_street',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

	<?php echo $form->textFieldGroup($model,'sloc_field',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array()))); ?>

	<?php echo $form->textFieldGroup($model,'sloc_position',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array()))); ?>

	<?php echo $form->textFieldGroup($model,'sloc_vertical',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array()))); ?>

<?php echo $form->dropDownListGroup($model, 'reserved_product_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Product::model()->findAll(array('order' => 'title')), 'id', 'barcode_and_title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
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
                $('#Sloc_section_id').html(data).selectpicker('refresh');
            }
        });
    }
</script>