

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'order-product-grid',
    'dataProvider' => $order_products->search(),
    'summaryText' => false,

    'filter' => null,
    'columns' => array(
        array(
            'header' => 'R.Br.',
            'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
            'htmlOptions' => array(
                'class' => 'text-right'
            )
        ),

        array(
            'name' => 'product_id',
            'value' => '$data->product ? $data->product->title : ""',
        ),
        array(
            'name' => 'package_id',
            'value' => '$data->package ? $data->package->title : ""',
        ),

        array(
            'name' => 'quantity',
            'htmlOptions' => array('class'=>'text-right'),
            'headerHtmlOptions' => array('class'=>'text-right'),
        ),

        array(
            'name' => 'paletts',
            'htmlOptions' => array('class'=>'text-right'),
            'headerHtmlOptions' => array('class'=>'text-right'),
        ),

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{delete}',
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