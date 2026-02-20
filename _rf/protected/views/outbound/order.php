<h5 class="col-xs-9 text-left">
    <?= $order->order_number;?> &bull; <?=$order->client->title;?>
</h5>
<h5 class="col-xs-3 text-right">
<a href="<?=Yii::app()->createUrl("/outbound/pickType/".$order->id);?>" class="btn btn-primary btn-xs">START</a>
</h5>
<div class="clearfix"></div>
<?php if ($missing != ''): ?>
    <div class="alert alert-danger">NEDOSTAJE:<ul><?=$missing;?></ul></div>
<?php endif; ?>

<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'order-product-grid',
    'dataProvider' => $model,
    'hideHeader' => true,
    'summaryText' => false,
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => null,
   // 'extraRowColumns' => array('product_barcode'),
   // 'extraRowExpression' => '"<strong>".$data["product_barcode"]."</strong> - " . Product::model()->findByPk($data["product_id"])->title',
    'rowCssClassExpression' => function($index,$data) {

        $class = '';
        if ($data['sscc_source'] === null) {
         //   $class .= 'text-muted alert-danger';
        }

        return $class;

    },
    'columns' => array(
        array(
            'header' => 'R.Br.',
            'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
            'htmlOptions' => array(
                'class' => 'text-right'
            )
        ),
        array(
            'name' => 'sloc_code',

        ),
        array(
            'name' => 'product_barcode',

        ),
       array(
           'name' => 'pick_palett',
           'type' => 'raw',
           'value' => '$data->pick_type == "palett" ? "<i class=\"glyphicon glyphicon-th-list\"></i>" : ($data->activity_palett_id == null ? "<i class=\"glyphicon glyphicon-globe\"></i>" : "")',
       ),
        array(
            'name' => 'target',

        ),



    ),
));