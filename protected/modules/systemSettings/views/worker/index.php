<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Workers') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'worker-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(

        'first_name',
        'last_name',
        // 'full_name',

        'email',
        array(
            'name' => 'workplace_id',
            'filter' => CHtml::listData(Workplace::model()->findAll(),'id','title'),
            'value' => '$data->workplace ? $data->workplace->title : ""',
        ),

        array(
            'name' => 'location_id',
            'filter' => CHtml::listData(Location::model()->findAll(),'id','title'),
            'value' => '$data->location ? $data->location->title : ""',
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
