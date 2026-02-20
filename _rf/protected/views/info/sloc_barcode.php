<h5>
    <div class="col-xs-10 text-left vertical-center">
        SLOC: <?= $sloc->sloc_code; ?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/info');?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>
<hr>

<?php if ($sloc_has_activity_palett) {
    $this->widget('ext.groupgridview.BootGroupGridView', array(
        'id' => 'order-product-grid',
        'dataProvider' => $sloc_has_activity_palett->search(),
        'hideHeader' => true,
        'summaryText' => false,

        'filter' => null,

        'extraRowColumns' => array('sscc'),
        'columns' => array(


            array(
                'header' => 'Proizvod',
                'type' => 'raw',
                'value' => function($data) {
                    $result = '';
                    foreach ($data->activityPalett->hasProducts as $hasProduct) {
                        $result .= $hasProduct->product_barcode. '<br>';
                    }
                    return $result;
                }


            ),
            array(
                'header' => 'Količina',
                'type' => 'raw',
                'value' => function($data) {
                    $result = '';
                    foreach ($data->activityPalett->hasProducts as $hasProduct) {
                        $result .= $hasProduct->content['quantity']. '<br>';
                    }
                    return $result;
                },
                'htmlOptions' => array('class' => 'text-right')


            ),


        ),
    ));
}

if ($sloc_has_product) {
    $this->widget('ext.groupgridview.BootGroupGridView', array(
        'id' => 'order-product-grid',
        'dataProvider' => $sloc_has_product->search(),
        'hideHeader' => true,
        'summaryText' => false,
        'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
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
}

