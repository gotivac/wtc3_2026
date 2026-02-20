<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Product Types') => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'title',
            array(
                'name' => 'description',
                'type' => 'raw',
                'value' => nl2br($model->description),
            ),
            array(
                'name' => 'has_children',
                'value' => $model->has_children == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
            ),
            'created_dt',
            'updated_dt',
        ),
    )); ?>
</div>