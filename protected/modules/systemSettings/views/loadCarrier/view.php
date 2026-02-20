<?php
$this->breadcrumbs=array(
	Yii::t('app','Load Carriers')=>array('index'),
	$model->title,
);

$this->menu=array(
array('label' => Yii::t('app', 'List'), 'url' => array('index')),
array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'title',
		'material',
		'width',
		'length',
		'height',
		'gross_weight',

		'created_dt',

		'updated_dt',
),
)); ?>
</div>