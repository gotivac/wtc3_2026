<?php
$this->breadcrumbs=array(
    Yii::t('app', 'Truck Types')=>array('index'),
	$model->title,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label'=>Yii::t('app','Update'),'url'=>array('update','id'=>$model->id)),
);
?>

<div class="alert-placeholder">
</div>
<div class="col-md-6">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
    array(
        'name' => 'gate_type_id',
        'value' => $model->gateType ? $model->gateType->title : '',
    ),
		'title',
		'title_en',

		'created_dt',

		'updated_dt',
),
)); ?>
</div>