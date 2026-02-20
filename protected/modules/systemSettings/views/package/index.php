<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Packages') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'package-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(

        'title',
        'material',
        array(
            'name' => 'width',
            'htmlOptions' => array('class' => 'text-right')
        ),

        array(
            'name' => 'length',
            'htmlOptions' => array('class' => 'text-right')
        ),
        array(
            'name' => 'height',
            'htmlOptions' => array('class' => 'text-right')
        ),
        array(
            'name' => 'gross_weight',
            'htmlOptions' => array('class' => 'text-right')
        ),
        array(
            'name' => 'product_count',
            'htmlOptions' => array('class' => 'text-right')
        ),
        array(
            'name' => 'load_carrier_count',
            'htmlOptions' => array('class' => 'text-right')
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
