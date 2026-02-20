<?php
$this->breadcrumbs=array(
    Yii::t('app','Activities')=>array('activity/index'),
    $model->activity->activityType->title . ' - ' . $model->activity->location->title . ' - ' . $model->activity->gate->title=>array('activity/update','id'=>$model->activity->id),
   $model->hasProducts[0]->sscc . ' - ' . $model->activityOrder->order_number
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('/activity/update/'.$model->activity->id.'?tab=2')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'activity-palett-grid',
    'dataProvider' => $products->search(),
    'summaryText' => false,

    'filter' => null,
    'columns' => array(
        array(
            'header' => 'R.Br.',
            'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
            'htmlOptions' => array(
                'class' => 'text-right'
            )
        ),


        array(
            'header' => Yii::t('app', 'Product'),
            'value' => '$data->product ? $data->product->internal_product_number . " - " . $data->product->title . " - " . $data->product->product_barcode :  ""',
        ),
        array(
            'name' => 'quantity',
            'value' => '$data->content["quantity"]',
            'htmlOptions' => array('class'=>'text-right'),
            'headerHtmlOptions' => array('class'=>'text-right'),
        ),
        array(
            'name' => 'packages',
            'value' => '$data->content["packages"]',
            'htmlOptions' => array('class'=>'text-right'),
            'headerHtmlOptions' => array('class'=>'text-right'),
        ),
        array(
            'name' => 'units',
            'value' => '$data->content["units"]',
            'htmlOptions' => array('class'=>'text-right'),
            'headerHtmlOptions' => array('class'=>'text-right'),
        ),


    ),
)); ?>
