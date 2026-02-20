<?php
$this->breadcrumbs=array(
	Yii::t('app','Mhe Failure Notices')=>array('index'),
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
                'name' => 'mhe_location_id',
                'value' => $model->mheLocation ? $model->mheLocation->title : '',
		),
		array(
                'name' => 'decription',
                'type' => 'raw',
                'value' => nl2br($model->description),
		),
		array(
                'name' => 'operates',
                'value' => $model->operates == 1 ? Yii::t('app','Yes') : Yii::t('app','No'),
		),
		'notice_datetime',
		'solution_datetime',

		'created_dt',

		'updated_dt',
),
)); ?>
</div>