<?php




$this->breadcrumbs = array(
    Yii::t('app', 'Order Requests') => array('index'),
    $model->activityType->title . ' - ' . $model->location->title . ' - ' . (is_array($model->orderNumber) ? implode('_',$model->orderNumber) : $model->orderNumber),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
    array('label' => '<i class="fa fa-truck"></i>' . "&nbsp;" . Yii::t('app', 'Create Activity'), 'url' => !$model->activity ? Yii::app()->createUrl("/activity/create/" . $model->id) : '','visible'=> $model->activity == null, 'encodeLabel'=>false),
    array('label' => '<i class="fa fa-truck"></i>' . "&nbsp;" . Yii::t('app', 'View Activity'), 'url' => $model->activity ? Yii::app()->createUrl("/activity/".$model->activity->id) : '','visible'=> $model->activity != null, 'encodeLabel'=>false),
    array('label' => Yii::t('app', 'Print'), 'url' => array('resPrint', 'id' => $model->id)),
);
?>

    <div class="alert-placeholder"><?php
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
        <h4><?= Yii::t('app', 'Order Details'); ?></h4>

        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data' => $model,
            'type' => 'bordered',
            'attributes' => array(
                'id',
                
                array(
                    'name' => 'urgent',
                    'value' => $model->urgent == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
                ),
                array(
                    'name' => 'activity_type_id',

                    'value' => $model->activityType ? $model->activityType->title : "",
                ),


                array(
                    'name' => 'location_id',

                    'value' => $model->location ? $model->location->title : "",
                ),
                'load_list',
                array(
                    'name' => Yii::t('app', 'Paletts'),
                    'value' => $model->totalPaletts,
                ),

                'created_dt',
                'updated_dt',

            ),
        )); ?>
        <p></p>
        <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
            'id' => 'order-product-grid',
            'dataProvider' => $order_products->perOrder(),
            'summaryText' => false,
            'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
            'filter' => null,
            'extraRowColumns' => array('order_client_id'),
            'extraRowExpression' => '"<strong>".$data->orderClient->client->title." - ".$data->orderClient->order_number." - " . $data->orderClient->customerSupplier->title."</strong>"',
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
                    'value' => '$data->product ? $data->product->internal_product_number : ""',
                ),
                array(
                    'name' => 'product_id',
                    'value' => '$data->product ? $data->product->title : ""',
                ),
                array(
                    'name' => 'product_id',
                    'value' => '$data->product ? $data->product->product_barcode : ""',
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

    </div>
    <div class="col-md-6">
        <?php if ($model->timeSlot): ?>

            <h4>
                <div class="text-left col-md-6 row">
                    <?= Yii::t('app', 'Time Slot'); ?>&nbsp;
                </div>
                <div class="text-right">
                    <a class="btn btn-default btn-xs" href="/timeSlot/<?= $model->timeSlot->id; ?>"><i
                                class="glyphicon glyphicon-eye-open"></i></a>
                    <?php if (!$model->activity): ?>
                    <a class="btn btn-default btn-xs" href="/timeSlot/update/<?= $model->timeSlot->id; ?>?tab=2"><i
                                class="glyphicon glyphicon-pencil"></i></a>
                    <button class="btn btn-danger btn-xs" id="removeTimeSlot"><i class="glyphicon glyphicon-trash"></i>
                        <?php endif; ?>
                    </button>
                </div>
            </h4>


            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data' => $model->timeSlot,
                'type' => 'bordered',
                'attributes' => array(
                    'id',
                    array(
                        'name' => 'location_id',
                        'value' => $model->timeSlot->location ? $model->timeSlot->location->title : '',
                    ),

                    array(
                        'name' => 'section_id',
                        'value' => $model->timeSlot->section ? $model->timeSlot->section->title : '',
                    ),
                    array(
                        'name' => 'gate_id',
                        'value' => $model->timeSlot->gate ? $model->timeSlot->gate->title : '',
                    ),
                    array(
                        'name' => 'activity_type_id',
                        'value' => $model->timeSlot->activityType ? $model->timeSlot->activityType->title : ''
                    ),
                    'defined_date',
                    'start_time',
                    'end_time',
                    array(
                        'name' => 'truck_type_id',
                        'value' => $model->timeSlot->truckType ? $model->timeSlot->truckType->title : ''
                    ),
                    'license_plate',
                    array(
                        'name' => Yii::t('app', 'Palletes'),
                        'value' => $model->timeSlot->totalPaletts,
                    ),

                    array(
                        'name' => Yii::t('app', 'Clients'),
                        'type' => 'raw',
                        'value' => function ($timeSlot) {
                            $clients = '<ul>';
                            foreach ($timeSlot->timeSlotDetails as $time_slot_detail) {
                                $clients .= '<li>' . $time_slot_detail->client->title . ' (' . $time_slot_detail->paletts . ')</li>';
                            }
                            $clients .= '</ul>';
                            return $clients;

                        }
                    ),

                    array(
                        'name' => 'notes',
                        'type' => 'raw',
                        'value' => nl2br($model->timeSlot->notes),
                    ),

                    'created_dt',

                    'updated_dt',

                ),
            )); ?>
        <?php endif; ?>

    </div>
<?php if ($model->timeSlot): ?>
    <script>
        $('#removeTimeSlot').on('click', function () {

            if (confirm('<?=Yii::t("app", "Are you sure?");?>')) {
                $.ajax({
                    url: '/timeSlot/delete/' + '<?=$model->timeSlot->id;?>' + '?ajax',
                    type: 'post',
                    success: function () {
                        location.href = location.href;
                    }
                })
            }
        })
    </script>
<?php endif; ?>