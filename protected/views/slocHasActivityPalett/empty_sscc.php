<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Empty Paletts') => array('resEmptySSCC'),
    Yii::t('app', 'List'),
);

?>

<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'enableSorting' => false,
    'id' => 'activity-paletts-grid',
    'dataProvider' => $model,
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'filter' => null,
    'mergeColumns' => array('activity_order_id'),
    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize)',
            'htmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
            'headerHtmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
        ),

        array(
            'header' => 'SLOC',
            'value' => '$data->inSloc ? $data->inSloc->sloc_code : ""'

        ),
        'sscc',
        array(
            'header' => Yii::t('app','Acceptance Date And Time'),
            'type' => 'raw',
            'value' => 'date("d.m.Y H:i",strtotime($data->created_dt))',
        ),
        array(
            'header' => Yii::t('app','Client'),
            'type' => 'raw',
            'value' => '$data->activityOrder  && $data->activityOrder->client ? $data->activityOrder->client->title : ""',

        ),
        array(
            'header' => Yii::t('app','Order'),
            'type' => 'raw',
            'value' => '$data->activityOrder ? $data->activityOrder->order_number : ""',

        ),

        array(
            'header' => Yii::t('app','Content'),
            'type' => 'raw',
            'value' => function($data) {
                $result = '';
                foreach ($data->hasProducts as  $item) {
                    $result .= ($item->product) ? $item->product->internal_product_number .' - '. $item->product->title.'<br>' : '';
                }
                $result = rtrim($result.'<br>');
                return $result;
            },
            'htmlOptions' => array('class' => 'col-md-4'),
        ),
        array(
            'header' => Yii::t('app','Product Barcode'),
            'type' => 'raw',
            'value' => function($data) {
                $result = '';
                foreach ($data->hasProducts as  $item) {
                    $result .= ($item->product) ? $item->product->product_barcode.'<br>' : '';
                }
                $result = rtrim($result.'<br>');
                return $result;
            },

        ),
        array(
            'header' => Yii::t('app','Quantity'),
            'type' => 'raw',
            'value' => function($data) {
                $result = '';
                foreach ($data->hasProducts as  $item) {
                    $result .= number_format($item->realQuantity,0,',','.').'<br>';
                }
                $result = rtrim($result.'<br>');
                return $result;
            },
            'htmlOptions' => array('class' => 'text-right','style'=>'width:80px'),
            'headerHtmlOptions' => array('class' => 'text-right')
        ),
        array(
            'header' => Yii::t('app','Acceptance Date'),
            'value' => 'date("d.m.Y",strtotime($data->created_dt))',
        ),

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{sticker} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'url' => 'Yii::app()->createUrl("/activityPalett/delete/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    )
                ),

                'sticker' => array(
                    'label' => '<i class="glyphicon glyphicon-barcode"></i>',
                    'url' => 'Yii::app()->createUrl("/activity/resSticker/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Sticker'),
                    )
                ),


            ),
        ),
    ),
)); ?>
