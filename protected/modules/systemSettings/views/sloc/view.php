<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Slocs') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>
<div class="alert-placeholder">

</div>
<div class="col-md-6">

    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'sloc_code',
            array(
                'name' => 'sloc_type_id',
                'value' => $model->slocType ? $model->slocType->title : null
            ),

            array(
                'name' => 'section_id',
                'value' => $model->section ? $model->section->title : null
            ),

            'sloc_street',
            'sloc_field',
            'sloc_position',
            'sloc_vertical',
            array(
                    'name' => 'reserved_product_id',
                    'value' => $model->reservedProduct ? ($model->reservedProduct->product_barcode . ' - ' . $model->reservedProduct->title) : '',
            ),

            'created_dt',

            'updated_dt',
        ),
    )); ?>
</div>