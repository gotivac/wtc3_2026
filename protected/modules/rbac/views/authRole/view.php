<?php
$this->breadcrumbs=array(
	'Auth Roles'=>array('index'),
	$model->title,
);

$this->menu=array(
array('label'=>'List AuthRole','url'=>array('index')),
array('label'=>'Create AuthRole','url'=>array('create')),
array('label'=>'Update AuthRole','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete AuthRole','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage AuthRole','url'=>array('admin')),
);
?>

<!-- <h1>View AuthRole #<?php echo $model->id; ?></h1> -->

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'title',
		'lower_case',
		'created_user_id',
		'created_dt',
		'updated_user_id',
		'updated_dt',
),
)); ?>
