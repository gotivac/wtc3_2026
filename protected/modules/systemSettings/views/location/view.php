<?php
$this->breadcrumbs=array(
	Yii::t('app','Locations')=>array('index'),
	$model->title,
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),
array('label'=>Yii::t('app','Update'),'url'=>array('update','id'=>$model->id)),
)

?>
<div class="alert-placeholder">

</div>
<div class="col-md-6">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'itemTemplate' => "<tr><th style='width: 25%;'>{label}</th><td>{value}</td></tr>",
'attributes'=>array(
		'id',
		'title',
        array(
                'name' => 'address',
            'type' => 'raw',
                'value' => nl2br($model->address)
        ),

		'email',
		'description',
        array(

                'name' => Yii::t('app','Surface'),
                'value' => $model->surface . " m2",
        ),
        'inbound_palletes',
        'inbound_trucks',
        'outbound_palletes',
        'outbound_trucks',
		'created_dt',
    	'updated_dt',
),
)); ?>
</div>