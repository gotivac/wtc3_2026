<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Mhe Failure Notices') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'mhe-failure-notice-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'mhe_location_id',
            'filter' => CHtml::listData(MheLocation::model()->findAll(array('order'=>'title')), 'id', 'title'),
            'value' => '$data->mheLocation ? $data->mheLocation->title : ""'
        ),

        array(
            'name' => 'description',
            'value' => 'nl2br($data->description)',
            'type' => 'raw',
        ),

        array(
            'name' => 'operates',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->operates) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
        array(
            'name' => 'created_user_id',
            'value' => '$data->user ? $data->user->name : ""',
        ),
        array(
            'name' => 'notice_datetime',
            'htmlOptions' => array('class' => 'col-md-2')
        ),

        array(
            'name' => 'solution_datetime',
            'htmlOptions' => array('class' => 'col-md-2')
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
