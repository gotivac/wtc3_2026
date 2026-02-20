<?php
$this->breadcrumbs=array(
    'Users'=>array('index'),
    $model->name,
);

$this->menu=array(
    array('label'=>Yii::t('app','List'),'url'=>array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

    <div class="alert-placeholder"></div>
<div class="col-md-4">
<?php $this->widget('booster.widgets.TbDetailView',array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'location_id',
        'name',
        'email',

        array(
            'name' => 'roles',
            //'value' => $model->authRole ?  $model->authRole->title : '',
        ),
        'notes',
        array(
            'name' => 'global_client',
            'value' => $model->global_client == 1 ? Yii::t('app','Yes') : Yii::t('app','No'),
        ),

        array(
                'name' => 'active',
            'value' => $model->active == 1 ? Yii::t('app','Yes') : Yii::t('app','No'),
        ),

    ),
)); ?>
</div>
