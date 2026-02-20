<?php
$this->breadcrumbs = array(
	Yii::t('app', 'Activities') => array('/activity/index'),
	$activity_order->activity->activityType->title . ' - ' . $activity_order->activity->location->title . ' - ' . $activity_order->activity->gate->title=>array('activity/update','id'=>$activity_order->activity->id),
	$activity_order->client->title,
	Yii::t('app', 'Products'),
);

$this->menu = array(
	array('label' => Yii::t('app', 'Back'), 'url' => array('activity/update/'.$activity_order->activity_id.'?tab=1')),
	// array('label' => '<i class="glyphicon glyphicon-barcode"></i> ' . Yii::t('app', 'Stickers'), 'url' => array('activityOrder/resStickers/'.$activity_order->id),'encodeLabel'=>false),

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
		<?php echo $this->renderPartial('_activity_order_products', array('model' => $model, 'activity_order_products' => $activity_order_products)); ?>
	</div>
</div>