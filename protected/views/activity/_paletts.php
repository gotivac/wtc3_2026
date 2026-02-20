

    <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
        'enableSorting' => false,
        'id' => 'activity-paletts-grid',
        'dataProvider' => $activity_paletts->search(),
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

            'sscc',
            array(
                'header' => Yii::t('app','SLOC'),
                'type' => 'raw',
                'value' => '$data->inSloc ? $data->inSloc->sloc->sloc_code : ""',
                'htmlOptions' => array('class' => 'text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),
            ),
            array(
                'header' => Yii::t('app','Storage Type'),
                'type' => 'raw',
                'value' => '$data->inSloc ? $data->inSloc->storageType->title : ""',
                'htmlOptions' => array('class' => 'text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),
            ),
            array(
                'name' => 'activity_order_id',
                'header' => Yii::t('app','Order'),
                'value' => '$data->activityOrder ? $data->activityOrder->order_number : ""',
                'htmlOptions' => array('class' => 'text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),

            ),
            array(

                'header' => Yii::t('app','Broj stavki'),
                'value' => 'count($data->hasProducts)',
                'htmlOptions' => array('class' => 'text-right'),
                'headerHtmlOptions' => array('class' => 'text-right'),


            ),

            array(

                'header' => Yii::t('app','Komada'),
                'value' => function ($data) {
                    $result = 0;
                    foreach ($data->hasProducts as $activity_palett_has_product) {
                        $result += $activity_palett_has_product->quantity;
                    }
                    return $result;
                },
                'htmlOptions' => array('class' => 'text-right'),
                'headerHtmlOptions' => array('class' => 'text-right'),


            ),

            array(
                'header' => Yii::t('app','Weight'),
                'value' => '$data->brutoWeight',
                'footer' => $model->brutoWeight,
                    'htmlOptions' => array('class' => 'text-right'),
                'headerHtmlOptions' => array('class' => 'text-right'),
                'footerHtmlOptions' => array('class' => 'text-right'),

            ),


            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{sticker} {delete} {products}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(
                    'sticker' => array(
                        'label' => '<i class="glyphicon glyphicon-barcode"></i>',
                        'url' => 'Yii::app()->createUrl("/activity/resSticker/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs view',
                            'title' => Yii::t('app', 'Sticker'),
                        )
                    ),


                    'delete' => array(
                        'label' => Yii::t('app', 'Delete'),
                        'url' => 'Yii::app()->createUrl("activity/ajaxDeletePalett/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs delete',

                        ),
                        'visible' => 'count($data->hasProducts) == 0'


                    ),
                    'products' => array(
                        'label' => '<i class="fa fa-dropbox"></i>',
                        'url' => 'Yii::app()->createUrl("/activityPalett/view/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs view',
                            'title' => Yii::t('app', 'Products'),
                        ),
                        'visible' => 'count($data->hasProducts) > 0'
                    ),
                ),
            ),
        ),
    )); ?>


