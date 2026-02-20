<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Slocs') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'sloc-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(
        'sloc_code',
        array(
            'name' => 'sloc_type_id',
            'filter' => CHtml::listData(SlocType::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->slocType ? $data->slocType->title : ""',
        ),

        array(
            'name' => 'section_id',
            'filter' => CHtml::listData(Section::model()->findAll(array('order' => 'title')), 'id', 'title'),
            'value' => '$data->section ? $data->section->title : ""',
        ),

        'sloc_street',
        array(
            'name' => 'sloc_field',
            'htmlOptions' => array('class' => 'col-md-1 text-center')
        ),
        array(
            'name' => 'sloc_position',
            'htmlOptions' => array('class' => 'col-md-1 text-center')
        ),
        array(
            'name' => 'sloc_vertical',
            'htmlOptions' => array('class' => 'col-md-1 text-center')
        ),
        array(
            'name'=>'reserved_product_barcode',

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
