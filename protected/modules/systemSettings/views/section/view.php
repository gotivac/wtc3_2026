<?php
$this->breadcrumbs = array(
    Yii::t('app','Sections') => array('index'),
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
    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            array(
                    'name' => 'location_id',
                    'value' => $model->location ? $model->location->title : '',
            ),

            'title',
            'code',
            'surface',
            array(
                'name' => 'wtc_managed',
                'value' => $model->wtc_managed ? Yii::t('app','Yes') : Yii::t('app','No'),
            ),
            array(
                'name' => 'is_customs',
                'value' => $model->is_customs ? Yii::t('app','Yes') : Yii::t('app','No'),
            ),

            'created_dt',

            'updated_dt',
        ),
    )); ?>
</div>
