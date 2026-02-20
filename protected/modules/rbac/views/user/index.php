<?php

$this->breadcrumbs = array(
    Yii::t('app', 'Users') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index'), 'active' => true),
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);
?>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => '{summary}{items}{pager}',
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'columns' => array(
        'name',
        'email',
        array(
            'name' => 'location_id',
            'type' => 'raw',
            'value' => '($data->location) ? $data->location->title : ""',
            'filter' => CHtml::listData(Location::model()->findAll(), 'id', 'title'),
        ),
        array(
            'name' => 'roles',
            'type' => 'raw',
            'value' => '$data->authRole ? $data->authRole->title : ""',
            'filter' => CHtml::listData(AuthRole::model()->findAll(), 'lower_case', 'title'),
        ),
        array(
            'name' => 'active',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->active) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
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
                    'visible' => 'false', //'$data->roles != "superadministrator"',
                )
            ),
        ),
    ),
));
?>
