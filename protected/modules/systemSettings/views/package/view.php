<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Packages') => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

    <?php $this->widget('booster.widgets.TbDetailView', array(
        'itemTemplate' => "<tr><th style='width: 25%;'>{label}</th><td>{value}</td></tr>",
        'data' => $model,
        'attributes' => array(
            'id',
            'title',
            'width',
            'length',
            'height',
            'gross_weight',

            'product_count',
            'load_carrier_count',
            'created_dt',
            'updated_dt',
        ),
    )); ?>
</div>