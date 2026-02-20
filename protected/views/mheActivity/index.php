<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Mhe Activities') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'mhe-activity-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(

        array(
            'name' => 'mhe_activity_type_id',
            'filter' => CHtml::listData(MheActivityType::model()->findAll(array('order'=>'title')), 'id', 'title'),
            'value' => '$data->mheActivityType ? $data->mheActivityType->title : ""'
        ),
        array(
            'name' => 'mhe_location_id',
            'filter' => CHtml::listData(MheLocation::model()->findAll(array('order'=>'title')), 'id', 'title'),
            'value' => '$data->mheLocation ? $data->mheLocation->title : ""'
        ),
        'date_and_time',
        array(
            'name' => 'notes',
            'value' => 'nl2br($data->notes)',
            'type' => 'raw',
        ),
        array(
            'name' => 'created_user_id',
            'value' => '$data->user ? $data->user->name : ""',
        ),

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{view} {update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs view'
                    )
                ),
                'update' => array(
                    'label' => Yii::t('app', 'Update'),
                    'options' => array(
                        'class' => 'btn btn-xs update'
                    )
                ),
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => (Yii::app()->params['adminDelete']),
                )
            ),
        ),
    ),
)); ?>
