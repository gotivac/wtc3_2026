<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Clients') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'client-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(

        'title',
        'official_title',
        'domain',
        'tax_number',
        array(
            'name' => 'location_id',
            'value' => '$data->location ? $data->location->title : ""',
            'filter' => CHtml::listData(Location::model()->findAll(array('order' => 'title')),'id','title')
        ),
        array(
            'name' => 'section_id',
            'value' => '$data->section ? $data->section->title : ""',
            'filter' => CHtml::listData(Section::model()->findAll(array('order' => 'title')),'id','title')
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
