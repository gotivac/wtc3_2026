<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Email Schedules') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'email-schedule-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(
        'title',
        'command',
        'action',
        array(
            'name' => 'recipients',
            'type' => 'raw',
            'value' => function ($data) {
                return str_replace(',', '<br>',$data->recipients);
        }),


            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{update}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(

                    'update' => array(
                        'label' => Yii::t('app', 'Update'),
                        'options' => array(
                            'class' => 'btn btn-xs update'
                        )
                    ),

                ),
            ),
        ),
    )); ?>
