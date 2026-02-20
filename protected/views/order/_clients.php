
<?php if (count($order_clients->search()->getData()) > 0) : ?>
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'order-client-grid',
        'dataProvider' => $order_clients->search(),
        'summaryText' => false,
        'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
        'filter' => null,
        'columns' => array(
            'order_number',
            array(
                'name' => 'client_id',
                'value' => '$data->client ? $data->client->title : ""'
            ),

            array(
                'name' => 'customer_supplier_id',
                'header' => $model->direction == 'in' ? Yii::t('app', 'Supplier') : Yii::t('app', 'Buyer'),
                'value' => '$data->customerSupplier ? $data->customerSupplier->title : ""'
            ),
            array(
                'header' => Yii::t('app', 'Products'),
                'type' => 'raw',
                'value' => function ($data) {
                    if (!empty($data->orderProducts)) {
                        $result = '<ul>';
                        foreach ($data->orderProducts as $orderProduct) {
                            $result .= '<li>' . $orderProduct->product->title . ': ' . $orderProduct->quantity . ' (' . $orderProduct->paletts . ')</li>';
                        }
                        $result .= '</ul>';
                    } else {
                        $result = CHtml::link(Yii::t('app','Add Products'),'/orderProduct/create/'.$data->id);
                    }
                    return $result;
                },
            ),

            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{product} {delete}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(
                    'product' => array(
                        'label' => '<i class="fa fa-dropbox"></i>',
                        'url' => 'Yii::app()->createUrl("orderProduct/create/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs view',
                            'title' => Yii::t('app', 'Products'),
                            /*
                            'evaluateOptions' => array('id'),
                            'id' => '$data->id',
                            */
                        ),

                    ),

                    'delete' => array(
                        'label' => Yii::t('app', 'Delete'),
                        'url' => 'Yii::app()->createUrl("order/ajaxDeleteClient/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs delete',

                        ),


                    )
                ),
            ),
        ),
    )); ?>

<?php endif; ?>
