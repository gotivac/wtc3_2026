<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Order Requests') => array('/order/index'),
    $order_client->orderRequest->activityType->title . ' - ' . $order_client->orderRequest->location->title . ' - ' . $order_client->orderRequest->load_list=>array('order/update','id'=>$order_client->orderRequest->id),
    $order_client->client->title,
    Yii::t('app', 'Products'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => array('order/update/'.$order_client->order_request_id.'?tab=1')),

);
?>
<div class="alert-placeholder">
    <?php
    $this->widget('booster.widgets.TbAlert', array(
        'fade' => true,
        'closeText' => '&times;', // false equals no close link
        'events' => array(),
        'htmlOptions' => array(),
        'userComponentId' => 'user',
        'alerts' => array( // configurations per alert type
            // success, info, warning, error or danger
            'success' => array('closeText' => '&times;'),
            'info', // you don't need to specify full config
            'warning' => array('closeText' => false),
            'error' => array('closeText' => Yii::t('app', 'Error')),
        ),
    ));
    ?>
</div>
<div class="row">
    <div class="col-md-3">
        <?php echo $this->renderPartial('_form', array('model' => $model, 'products' => $products)); ?>
    </div>
    <div class="col-md-9">
        <?php echo $this->renderPartial('_order_products', array('model' => $model, 'order_products' => $order_products)); ?>
    </div>
</div>