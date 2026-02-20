<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Sections') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'section-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'mergeColumns' => array('location_id'),
    'columns' => array(
        array(
            'name' => 'location_id',
            'filter' => CHtml::listData(Location::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->location ? $data->location->title : ""',
        ),
        'title',
        'code',



        array(
            'name' => 'surface',
            'value' => '$data->surface ? $data->surface." m2" : ""',
            'htmlOptions' => array('class' => 'col-md-1 text-right')
        ),

        array(
            'name' => 'wtc_managed',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->wtc_managed) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
        array(
            'name' => 'is_customs',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->is_customs) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
        'customs_warehouse_number',
        'customs_office_code',
        'customs_warehouse_type',

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
