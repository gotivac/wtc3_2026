<?php
$this->breadcrumbs=array(
	Yii::t('app','Mhe Activities')=>array('index'),
	$model->id,
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
    'type' => 'bordered',
'attributes'=>array(
		'id',
    array(
        'name' => 'mhe_activity_type_id',
        'value' => $model->mheActivityType ? $model->mheActivityType->title : '',
    ),
    array(
        'name' => 'mhe_location_id',
        'value' => $model->mheLocation ? $model->mheLocation->title : '',
    ),
		'date_and_time',
    array(
        'name' => 'notes',
        'type' => 'raw',
        'value' => nl2br($model->notes),
    ),

		'created_dt',

		'updated_dt',
),
)); ?>
</div>