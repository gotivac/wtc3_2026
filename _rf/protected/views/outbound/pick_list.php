<h5 class="col-xs-9 text-left">
    <?= $order->order_number;?> &bull; <?=$order->client->title;?>
</h5>
<h5 class="col-xs-3 text-right">
<a href="<?=Yii::app()->createUrl("/outbound/pickList/".$order->id);?>" class="btn btn-primary btn-xs">START</a>
</h5>



<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'order-product-grid',
    'dataProvider' => $model,
    'hideHeader' => true,
    'summaryText' => false,
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => null,
    'extraRowColumns' => array('pick_type'),
    'extraRowExpression' => '"<strong>".$data["pick_type_text"]."</strong>"',

    'columns' => array(

        array(
            'name' => 'sloc_code',
            'type' => 'raw',
            'value' => 'CHtml::link($data["sloc_code"],Yii::app()->createUrl("/outbound/pick/" . $data["activity_palett_id"])) . " - " . Product::model()->findByPk($data["product_id"])->title',


        ),
       array(
           'name' => 'pick_palett',
           'type' => 'raw',
           'value' => '$data["pick_palett"] ? "<i class=\"glyphicon glyphicon-th-list\"></i>" : ""',
       ),
        array(
            'name' => 'pick_quantity',

        ),



    ),
));