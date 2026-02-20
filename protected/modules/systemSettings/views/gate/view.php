<?php
$this->breadcrumbs=array(
    Yii::t('app', 'Gates')=>array('index'),
	$model->title,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder">

</div>
<div class="col-md-6">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',


		'title',
		'code',
    array(

        'name' => Yii::t('app','Sections'),
        'type' => 'raw',
        'value' => function($model) {
            $result = '';
            foreach($model->sections as $section) {
                $result .= $section->title. '<br>';
            }
            return $result;
}
    ),
    array(
        'name' => 'gate_type_id',
        'value' => $model->gateType ? $model->gateType->title : '',
    ),
    array(
        'name' => 'tms_gate',
        'value' => $model->tms_gate ? Yii::t('app','Yes') : Yii::t('app','No'),
    ),


		'created_dt',

		'updated_dt',
),
)); ?>
</div>