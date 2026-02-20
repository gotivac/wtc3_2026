<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Auth Roles') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label'=>Yii::t('app','List'),'url'=>array('index'),'active'=>true),
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'auth-role-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array('class' => 'col-md-1 text-right')
        ),

        'title',
        'lower_case',

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
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
