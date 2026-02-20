<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Mhe Locations') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'mhe-location-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(

        array(
            'name' => 'mhe_type_id',
            'value' => '$data->mheType ? $data->mheType->title : ""',
            'filter' => CHtml::listData(MheType::model()->findAll(),'id','title'),
        ),
        array(
            'name' => 'location_id',
            'value' => '$data->location ? $data->location->title : ""',
            'filter' => CHtml::listData(Location::model()->findAll(),'id','title'),
        ),


        'title',
        'code',
        'serial_number',

        'description',
        'failure_email',


        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{view} {update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs update'
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
