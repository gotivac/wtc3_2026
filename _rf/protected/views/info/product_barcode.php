<h5>
    <div class="col-xs-10 text-left vertical-center">
        PROIZVOD: <?= $product->product_barcode; ?><br><?= $product->title; ?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?= Yii::app()->createUrl('/info'); ?>"><i
                    class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>
<div class="clearfix"></div>
<hr>

<h4>KOMERCIJALNI DEO:</h4>
<?php
$this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'order-product-grid',
    'dataProvider' => $activity_palett_has_product,
    'hideHeader' => true,
    'summaryText' => false,

    'filter' => null,

    'extraRowColumns' => array('sscc'),
    'extraRowExpression' => function ($data) {
        if ($data->activityPalett->inSloc) {
            return $data->sscc . " u <b>" . $data->activityPalett->inSloc->sloc_code . "<b>";
        }
        $pick = Pick::model()->findByAttributes(array('sscc_destination' => $data->activityPalett->sscc, 'product_id' => $data->product_id));

        if ($pick === null) {
            return $data->sscc . " u Gate IN";
        }
        return $data->sscc . " u Gate OUT";

    },
    'columns' => array(


        array(
            'header' => 'Proizvod',
            'type' => 'raw',
            'value' => '$data->product->title',
        ),
        array(
            'header' => 'Količina',
            'type' => 'raw',
            'value' => '$data->content["quantity"]',
            'htmlOptions' => array('class' => 'text-right'),


        ),


    ),
));
?>
<h4>WEB PRODAJA:</h4>
<?php
$this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'order-product-grid',
    'dataProvider' => $sloc_has_product->search(),
    'hideHeader' => true,
    'summaryText' => false,
    'extraRowColumns' => array('sloc_code'),
    'filter' => null,


    'columns' => array(

        array(
            'header' => 'Proizvod',
            'type' => 'raw',
            'value' => '$data->product_barcode',


        ),
        array(
            'header' => 'Količina',
            'type' => 'raw',
            'value' => '$data->realQuantity',
            'htmlOptions' => array('class' => 'text-right')

        ),


    ),
));


