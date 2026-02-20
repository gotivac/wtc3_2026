<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Gates') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    // 'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(
        'title',
        'code',
        array(
            'name' => 'location_id',
            'filter' => CHtml::listData(Location::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->location ? $data->location->title : ""',
        ),
        /*
        array(
            'name' => 'section_id',
            'filter' => CHtml::listData(Section::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->section ? $data->section->title : ""',
        ),
        */
        array(
            'name' => 'gate_type_id',
            'filter' => CHtml::listData(GateType::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->gateType ? $data->gateType->title : ""',
        ),

        array(
            'name' => 'tms_gate',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->tms_gate) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
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
