<?php
$this->breadcrumbs=array(
    Yii::t('app','Workers')=>array('index'),
	$model->full_name,
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
    'first_name',
    'last_name',
    'full_name',
    'email',
    array(
        'name' => 'workplace_id',
        'value' => $model->workplace ? $model->workplace->title : '',
    ),
    array(
        'name' => 'location_id',
        'value' => $model->location ? $model->location->title : '',
    ),


		'created_dt',

		'updated_dt',
),
)); ?>
</div>