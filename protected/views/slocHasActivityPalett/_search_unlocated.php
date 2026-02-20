<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

<div class="col-md-2">
    <?php echo $form->textFieldGroup($model, 'sscc', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Product'); ?></label>
        <?php echo CHtml::dropDownList('product_id', '', CHtml::listData(Product::model()->findAll(), 'id', 'internal_product_number'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?=Yii::t('app','Acceptance Date');?></label>
        <?php $this->widget('booster.widgets.TbDatePicker', array(
            'model' => $model,
            'attribute' => 'created_dt',
            'options' => array(
                'format' => 'dd.mm.yyyy',
                'language' => 'rs-latin',
                'autoclose' => 'true'
            ),
            'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
    </div>
</div>

<div class="col-md-1">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => 'Filter',
        )); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<div class="clearfix"></div>
<hr>
