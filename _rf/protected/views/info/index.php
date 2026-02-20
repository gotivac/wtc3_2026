<h5>
    <div class="text-center col-xs-12">
        INFO
    </div>

</h5>
<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'activity-palett-has-product-form',
    'type'=> 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div class="col-md-12">
<div class="form-group">
    <?php echo CHtml::textField('sloc_barcode', '', array('class' => 'submit form-control','placeholder' => 'SLOC barkod')); ?>
</div>
<div class="form-group">
    <?php echo CHtml::textField('sscc_barcode', '', array('class' => 'submit form-control','placeholder' => 'SSCC barkod')); ?>
</div>
    <div class="form-group">
        <?php echo CHtml::textField('product_barcode', '', array('class' => 'submit form-control','placeholder' => 'Barkod proizvoda')); ?>
    </div>



<div class="form-actions">
        <button type="submit" class="btn btn-primary btn-small">OK</i></button>
</div>

</div>
<?php $this->endWidget(); ?>