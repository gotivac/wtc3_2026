<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Products') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            array(
                'name' => Yii::t('app', 'Client'),
                'value' => $model->client ? $model->client->title : '',
            ),

            array(
                'name' => Yii::t('app', 'Product Type'),
                'value' => $model->productType ? $model->productType->title : '',
            ),
            array(

                'name' => Yii::t('app', 'Packages'),
                'type' => 'raw',
                'value' => function ($model) {
                    $result = '';
                    foreach ($model->packages as $package) {
                        $is_default = false;
                        $product_has_package = ProductHasPackage::model()->findByAttributes(array('product_id' => $model->id, 'package_id' => $package->id));
                        if ($product_has_package && $product_has_package->package_id == $model->package_id) {
                            $is_default = true;
                        }

                        $result .= $is_default ? '<strong>' . $package->title . '</strong> <i class="fa fa-check"></i><br>' : $package->title . '<br>';
                    }
                    return $result;
                }
            ),


            array(
                'name' => Yii::t('app', 'Load Carrier'),
                'value' => $model->loadCarrier ? $model->loadCarrier->title : '',
            ),
            'external_product_number',
            'internal_product_number',
            'product_barcode',
            'title',
            'description',
            'width',
            'length',
            'height',
            'weight',


            'created_dt',

            'updated_dt',
        ),
    )); ?>
</div>