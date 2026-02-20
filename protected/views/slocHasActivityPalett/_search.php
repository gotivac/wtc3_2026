<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'id' => 'filter-form'
)); ?>
<div class="col-md-1">
    <?php echo $form->textFieldGroup($model, 'sloc_code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>
<div class="col-md-2">

    <?php echo $form->textFieldGroup($model, 'sscc', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>
<div class="col-md-1">
    <?php echo $form->dropDownListGroup($model, 'storage_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(StorageType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>
</div>

<!--
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Client'); ?></label>
        <?php echo CHtml::dropDownList('client_id', '', CHtml::listData(Client::model()->findAll(), 'id', 'title'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Product'); ?></label>
        <?php echo CHtml::dropDownList('product_id', '', CHtml::listData(Product::model()->findAll(), 'id', 'internal_product_number'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
</div>
-->
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Product Barcode'); ?></label>
        <?php echo CHtml::textField('product_barcode', '', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="col-md-1">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Order'); ?></label>
        <?php echo CHtml::textField('order_number', '',  array('class' => 'form-control')); ?>
    </div>
</div>
<div class="col-md-1">
    <div class="form-group">
        <label class="control-label"><?=Yii::t('app','Created');?></label>
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
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Status palete'); ?></label>
        <?php echo CHtml::dropDownList('status', '', array('' => '','touched'=>'Delimično pikovane','picked'=>'Cele pikovane'), array('class' => 'form-control selectpicker')); ?>
    </div>
</div>

<div class="col-md-1 text-right">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => 'Filter',
            'htmlOptions' => array('name' => 'excel','class'=>'col-md-12'),
        )); ?>
    </div>
</div>
<div class="col-md-1 text-right">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'default',
            'context' => 'success',
            'label' => 'Excel',
            'id' => 'excel',
            'htmlOptions' => array('name' => 'excel','class'=>'col-md-12'),

        )); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<div class="clearfix"></div>
<hr>

<script>
    $('#excel').on('click',function(){
        let data = $('#filter-form').serialize();
        location.href=location.href+'?'+data+'&excel=true';
    })
</script>