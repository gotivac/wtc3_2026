

<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'enableSorting' => false,
    'id' => 'attachments-grid',
    'dataProvider' => $activity_attachments->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'filter' => null,

    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize)',
            'htmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
            'headerHtmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
        ),


        array(
        'name' => 'filename',
),


        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{download} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'download' => array(
                    'label' => '<i class="glyphicon glyphicon-file"></i>',
                    'url' => 'Yii::app()->createUrl("/activity/resDownloadAttachment/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Download'),
                    )
                ),


                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'url' => 'Yii::app()->createUrl("activity/ajaxDeleteAttachment/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs delete',

                    ),



                ),
            ),
        ),
    ),
)); ?>


