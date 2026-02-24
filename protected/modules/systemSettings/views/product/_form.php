<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'product-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>



<?php echo $form->dropDownListGroup($model, 'client_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Client::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->dropDownListGroup($model, 'product_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(ProductType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<?php echo $form->dropDownListGroup($model, 'load_carrier_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(LoadCarrier::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>

<?php // echo $form->dropDownListGroup($model, 'package_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(LoadCarrier::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>


<div class="form-group">
    <label class=" col-sm-3 control-label"><?= Yii::t('app', 'Packages'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?= CHtml::dropDownList('ProductHasPackage[package_id]', $package_ids, CHtml::listData(Package::model()->findAll(), 'id', 'concatened'), array('class' => 'form-control selectpicker', 'multiple' => 'multiple')); ?>
    </div>
</div>

<div id="default-package" class="<?= !empty($package_ids) ? '' : ' hidden';?>">
<!--    <label class=" col-sm-3 control-label"><?= Yii::t('app', 'Default Package'); ?></label> -->
    <!-- <div class="col-md-6 col-sm-12 col-sm-9"> -->
        <?php // echo CHtml::dropDownList('ProductHasPackage[is_default]', $is_default, $is_default ? Chtml::listData(Package::model()->findAll(array('condition' => 'id IN ('.implode(',',$package_ids).')')),'id','concatened') : array(), array('class' => 'form-control','data-search' => false)); ?>
        <?php $packages = !empty($package_ids) ? Package::model()->findAll(array('condition'=>'id IN ('.implode(',',$package_ids).')')) : array();?>
        <?=  $form->dropDownListGroup($model, 'package_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData($packages, 'id', 'concatened'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
    <!-- </div> -->
</div>
<?php echo $form->textFieldGroup($model, 'external_product_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'internal_product_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textAreaGroup($model, 'description', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>

<?php echo $form->textFieldGroup($model, 'width', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)), 'append' => 'm')); ?>

<?php echo $form->textFieldGroup($model, 'length', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)), 'append' => 'm')); ?>

<?php echo $form->textFieldGroup($model, 'height', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)), 'append' => 'm')); ?>

<?php echo $form->textFieldGroup($model, 'weight', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)), 'append' => 'kg')); ?>
<?php echo $form->textFieldGroup($model, 'volume', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)), 'append' => 'm3')); ?>

<?php // echo $form->textFieldGroup($model, 'pieces_in_package', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array()))); ?>

<?php // echo $form->textFieldGroup($model, 'packages_on_pallet', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array()))); ?>

<?php echo $form->textFieldGroup($model, 'stock_minimum', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)))); ?>

<?php echo $form->textFieldGroup($model, 'stock_maximum', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 10)))); ?>
<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $('#ProductHasPackage_package_id').on('change', function () {
        let ids = $(this).val();
        if (ids != null) {
            $.ajax({
                url: '<?= Yii::app()->createUrl("systemSettings/package/ajaxDefaultDropdown");?>',
                type: 'post',
                data: {'ids': ids},
                dataType: 'html',
                success: function (data) {
                    if (data.length > 0) {
                        $('#default-package').removeClass('hidden');
                    }
                    $('#Product_package_id').html(data).selectpicker('refresh');
                }
            });
        } else {
            $('#Product_package_id').html('').selectpicker('refresh');
            $('#default-package').addClass('hidden');
        }
    });
</script>