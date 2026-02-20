<?php
$this->breadcrumbs=array(
	Yii::t('app','Sloc Has Products')=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>Yii::t('app','Back'),'url'=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'sloc_id',
		'sloc_code',
		'product_id',
		'product_barcode',
		'quantity',
		'created_user_id',
		'created_dt',
		'updated_user_id',
		'updated_dt',
),
)); ?>
</div>