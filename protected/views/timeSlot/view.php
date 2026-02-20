<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Time Slots') => array('index'),
    $model->defined_date . ': ' . $model->start_time . '-' . $model->end_time
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id, 'tab' => 2)),
    array('label' => '<i class="fa fa-truck"></i>' . "&nbsp;" . Yii::t('app', 'Create Activity'), 'url' => $model->order ? Yii::app()->createUrl("/activity/create/" . $model->order->id) : '','visible'=>$model->order && !$model->order->activity, 'encodeLabel'=>false),
    array('label' => '<i class="fa fa-truck"></i>' . "&nbsp;" . Yii::t('app', 'View Activity'), 'url' => $model->order && $model->order->activity ? Yii::app()->createUrl("/activity/".$model->order->activity->id) : '','visible'=>$model->order && $model->order->activity, 'encodeLabel'=>false),
);
?>

    <div class="alert-placeholder">
        <?php
        $this->widget('booster.widgets.TbAlert', array(
            'fade' => true,
            'closeText' => '&times;', // false equals no close link
            'events' => array(),
            'htmlOptions' => array(),
            'userComponentId' => 'user',
            'alerts' => array( // configurations per alert type
                // success, info, warning, error or danger
                'success' => array('closeText' => '&times;'),
                'info', // you don't need to specify full config
                'warning' => array('closeText' => false),
                'error' => array('closeText' => Yii::t('app', 'Error')),
            ),
        ));
        ?>
    </div>
    <div class="col-md-6">
        <h4><?= Yii::t('app', 'Time Slot'); ?></h4>
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data' => $model,
            'type' => 'bordered',
            'attributes' => array(
                'id',
                array(
                    'name' => 'location_id',
                    'value' => $model->location ? $model->location->title : '',
                ),
                array(
                    'name' => 'section_id',
                    'value' => $model->section ? $model->section->title : '',
                ),
                array(
                    'name' => 'gate_id',
                    'value' => $model->gate ? $model->gate->title : '',
                ),
                array(
                    'name' => 'activity_type_id',
                    'value' => $model->activityType ? $model->activityType->title : ''
                ),
                'defined_date',
                'start_time',
                'end_time',
                array(
                    'name' => 'truck_type_id',
                    'value' => $model->truckType ? $model->truckType->title : ''
                ),
                'license_plate',
                array(
                    'name' => Yii::t('app', 'Palletes'),
                    'value' => $model->totalPaletts,
                ),
                array(
                    'name' => Yii::t('app', 'Clients'),
                    'type' => 'raw',
                    'value' => function ($model) {
                        $clients = '<ul>';
                        foreach ($model->timeSlotDetails as $time_slot_detail) {
                            $clients .= '<li>' . $time_slot_detail->client->title . ' (' . $time_slot_detail->paletts . ')</li>';
                        }
                        $clients .= '</ul>';
                        return $clients;

                    }
                ),

                array(
                    'name' => 'notes',
                    'type' => 'raw',
                    'value' => nl2br($model->notes),
                ),

                'created_dt',

                'updated_dt',
            ),
        )); ?>
    </div>
<?php if ($model->order): ?>
    <div class="col-md-6">
        <h4>
            <div class="text-left col-md-6 row">
                <?= Yii::t('app', 'Order Details'); ?>&nbsp;
            </div>
            <div class="text-right">
                <a class="btn btn-default btn-xs" href="/order/<?= $model->order->id; ?>"><i
                            class="glyphicon glyphicon-eye-open"></i></a>


            </div>
        </h4>

        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data' => $model->order,
            'type' => 'bordered',
            'attributes' => array(

                'id',

                array(
                    'name' => 'urgent',
                    'value' => $model->order->urgent == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
                ),

                array(
                    'name' => 'activity_type_id',

                    'value' => $model->order->activityType ? $model->order->activityType->title : "",
                ),


                array(
                    'name' => 'location_id',

                    'value' => $model->order->location ? $model->order->location->title : "",
                ),

                'load_list',
                array(
                    'name' => Yii::t('app', 'Paletts'),
                    'value' => $model->order->totalPaletts,
                ),

                'created_dt',
                'updated_dt',

            ),
        )); ?>


        <?php if (count($order_products->perOrder()->getData()) > 0):?>
        <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
            'id' => 'order-product-grid',
            'dataProvider' => $order_products->perOrder(),
            'summaryText' => false,
            'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
            'filter' => null,
            'extraRowColumns' => array('order_client_id'),
            'extraRowExpression' => '"<strong>".$data->orderClient->client->title." - ".$data->orderClient->order_number."</strong>"',
            'columns' => array(

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
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),

                array(
                    'name' => 'paletts',
                    'htmlOptions' => array('class' => 'text-right'),
                    'headerHtmlOptions' => array('class' => 'text-right'),
                ),


            ),
        )); ?>
    <?php endif; ?>
    </div>

<?php endif; ?>