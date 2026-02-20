<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Mhe Types') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'mhe-type-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(

        'title',
        'description',

        /*
        'updated_dt',
        */
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
