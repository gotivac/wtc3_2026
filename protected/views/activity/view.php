<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Activities') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id), 'visible' => $model->truck_dispatch_time == null || $this->user->roles == 'superadministrator'),
    array('label' => Yii::t('app', 'Receipt'), 'url' => array('resReceipt', 'id' => $model->id), 'visible' => $model->direction == 'in'),
    array('label' => Yii::t('app', 'Delivery Note'), 'url' => array('resDeliveryNote', 'id' => $model->id), 'visible' => $model->direction == 'out'),
    array('label' => Yii::t('app', 'Zapisnik o razlikama'), 'url' => array('resInboundIssues', 'id' => $model->id), 'visible' => $model->direction == 'in' && $model->inboundIssues() != false),

);
?>

    <div class="alert-placeholder"></div>
    <h4><?= Yii::t('app', 'Activity'); ?></h4>
    <hr>
    <div class="row">


        <div class="col-md-6">

            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data' => $model,
                'type' => 'bordered',


                'attributes' => array(
                    'id',
                    array(
                        'name' => 'urgent',
                        'value' => $model->urgent == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                    ),
                    array(
                        'name' => 'activity_type_id',
                        'value' => $model->activityType ? $model->activityType->title : 'N/A',
                    ),
                    array(
                        'name' => 'location_id',
                        'value' => $model->location ? $model->location->title : 'N/A',
                    ),


                    array(
                        'name' => 'gate_id',
                        'value' => $model->gate ? $model->gate->title : 'N/A',
                    ),

                    array(
                        'name' => 'truck_arrived_datetime',
                        'type' => 'raw',
                        'value' => function ($model) {
                            $result = $model->truck_arrived_datetime;
                            if ($model->orderRequest && $model->orderRequest->timeSlot) {
                                $result .= ' &bull; <i class="text-muted text-right">' . $model->orderRequest->timeSlot->defined_date . ' ' . $model->orderRequest->timeSlot->start_time . ' &nbsp;<i title="Time Slot" style="cursor:pointer" class="glyphicon glyphicon-info-sign"></i>';
                            }
                            return $result;
                        }
                    ),

                    'license_plate',
                    'shipper_data',
                    'driver_data',
                    array(
                        'name' => 'driver_present',
                        'value' => $model->driver_present == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                    ),
                    array(
                        'name' => 'truck_checked',
                        'value' => $model->truck_checked == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                    ),
                    array(
                        'name' => Yii::t('app', 'Paletts'),
                        'type' => 'raw',
                        'value' => function ($model) {
                            $result = $model->totalPaletts;
                            if ($model->orderRequest && ($model->orderRequest->totalPaletts != $model->totalPaletts)) {
                                $result .= ' <i class="alert-danger glyphicon glyphicon-exclamation-sign" title="' . Yii::t('app', 'Expected Palett Count') . ': ' . $model->orderRequest->totalPaletts . '"></i>';
                            }
                            return $result;
                        }
                    ),

                    array(
                        'name' => 'truck_dispatch_datetime',
                        'type' => 'raw',
                        'value' => function ($model) {
                            $result = $model->truck_dispatch_datetime;
                            if ($model->orderRequest && $model->orderRequest->timeSlot) {
                                $result .= ' &bull; <i class="text-muted text-right">' . $model->orderRequest->timeSlot->defined_date . ' ' . $model->orderRequest->timeSlot->end_time . ' &nbsp;<i title="Time Slot" style="cursor:pointer" class="glyphicon glyphicon-info-sign"></i>';
                            }
                            return $result;
                        }
                    ),

                    array(
                        'name' => 'system_acceptance',
                        'value' => $model->system_acceptance == 1 ? $model->system_acceptance_datetime : Yii::t('app', 'No'),
                    ),

                    array(
                        'name' => 'customs',
                        'value' => $model->customs == 1 ? $model->customs_datetime : Yii::t('app', 'No'),
                        'visible' => $model->direction == 'in'
                    ),

                    'notes',
                    array(
                        'name' => Yii::t('app', 'Documents'),
                        'type' => 'raw',
                        'value' => function ($model) {
                            $result = '';
                            foreach ($model->attachments as $attachment) {
                                $result .= CHtml::link($attachment->filename, Yii::app()->createUrl('/activity/resDownloadAttachment/' . $attachment->id)) . '<br>';
                            }
                            $result = rtrim($result, '<br>');
                            return $result;
                        }
                    ),
                    'created_dt',
                    'updated_dt',
                ),
            )); ?>
        </div>


        <div class="col-md-6">
            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger">
                    <h4>Neispravne količine proizvoda:</h4>
                    <table class="table items">
                        <tr>
                            <td>Barkod proizvoda</td>
                            <td>Naziv proizvoda</td>
                            <td class="text-right">Tražena količina</td>
                            <td class="text-right">Rezervisana količina</td>
                        </tr>
                        <?php foreach ($errors as $error): ?>
                        <tr>
                        <td>
                            <?=$error['product_barcode'];?>
                        </td>
                            <td>
                                <?=$error['product_title'];?>
                            </td>
                            <td class="text-right">
                                <?=$error['target'];?>
                            </td>
                            <td class="text-right">
                                <?=$error['quantity'];?>
                            </td>
                        <?php endforeach; ?>
                        </tr>
                    </table>

                </div>
            <?php endif; ?>
            <details open>
                <summary><?= Yii::t('app', 'Products'); ?> <span class="bs-caret"><span class="caret"></span></span>
                </summary>

                <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
                    'id' => 'activity-product-grid',
                    'dataProvider' => $products->search(),
                    'summaryText' => false,
                    'filter' => null,
                    'extraRowColumns' => array('sscc'),
                    'extraRowExpression' => '"<strong>".$data->sscc."</strong>"',
                    'rowCssClassExpression' => function ($index, $data) {

                        $class = '';

                        if ($data->activityPalett->activity->direction == 'in' && !$data->activityPalett->isLocated()) {
                            $class .= 'text-muted alert-warning';
                        }

                        if ($data->activityPalett->activity->direction == 'out' && !$data->activityPalett->isLoaded()) {
                            $class .= 'text-muted alert-warning';
                        }


                        return $class;

                    },

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
                            'type' => 'raw',
                            'value' => '$data->product ? $data->product->title . " &bull; " . $data->product->product_barcode  : ""',
                        ),
                        array(

                            'name' => 'quantity',
                            'value' => '$data->quantity',
                            'htmlOptions' => array('class' => 'text-right'),
                            'headerHtmlOptions' => array('class' => 'text-right'),
                        ),
                        array(

                            'name' => 'volume',
                            'type' => 'raw',
                            'value' => '$data->volume . " m<sup>3</sup>"',
                            'htmlOptions' => array('class' => 'text-right'),
                            'headerHtmlOptions' => array('class' => 'text-right'),
                        ),
                        array(
                            'name' => 'packages',
                            'htmlOptions' => array('class' => 'text-right'),
                            'headerHtmlOptions' => array('class' => 'text-right'),

                        ),
                        array(
                            'name' => 'units',
                            'htmlOptions' => array('class' => 'text-right'),
                            'headerHtmlOptions' => array('class' => 'text-right'),

                        ),


                    ),
                )); ?>
            </details>
            <?php if ($picks): ?>
                <p></p>
                <details open>
                    <summary><?= Yii::t('app', 'Picks'); ?> <span class="bs-caret"><span class="caret"></span></span>

                    </summary>
                    <p>
                    <div class="text-right"><a
                                href="<?= Yii::app()->createUrl('/activity/resPicksExportToExcel/' . $model->id); ?>"
                                class="btn btn-small btn-success">Excel</a></div>
                    </p>
                    <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
                        'id' => 'picks-grid',
                        'dataProvider' => $picks->activitySearch(),
                        'summaryText' => false,
                        'filter' => null,

                        'extraRowColumns' => array('activity_order_id'),
                        'extraRowExpression' => '"<strong>".$data->activityOrder->order_number."</strong>"',
                        'rowCssClassExpression' => function ($index, $data) {

                            $class = '';

                            if ($data->quantity > 0) {
                                if ($data->quantity == $data->target) {
                                    $class .= 'success';
                                } else {
                                    $class .= 'danger';
                                }
                            }
                            return $class;
                        },

                        'columns' => array(

                            array(
                                'header' => 'R.Br.',
                                'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
                                'htmlOptions' => array(
                                    'class' => 'text-right'
                                )
                            ),

                            array(
                                'name' => 'sloc_code',
                                'type' => 'raw',

                            ),
                            array(
                                'name' => 'sscc_source',

                            ),
                            array(
                                'name' => 'product_id',
                                'value' => '$data->product ? $data->product->internal_product_number : ""',
                            ),
                            array(
                                'header' => Yii::t('app', 'Product Barcode'),
                                'value' => '$data->product ? $data->product->product_barcode : ""',
                            ),
                            array(
                                'name' => 'target',
                                'htmlOptions' => array('class' => 'text-right'),
                                'headerHtmlOptions' => array('class' => 'text-right'),

                            ),
                            array(
                                'header' => 'Pikovano',
                                'name' => 'quantity',
                                'htmlOptions' => array('class' => 'text-right'),
                                'headerHtmlOptions' => array('class' => 'text-right'),

                            ),


                        ),
                    )); ?>
                </details>
            <?php endif; ?>
        </div>
    </div>
<?php if ($order_request && $order_products): ?>
    <p></p>
    <h4><?= Yii::t('app', 'Order'); ?></h4>
    <hr>
    <div class="row">

        <div class="col-md-6">


            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data' => $order_request,
                'type' => 'bordered',
                'attributes' => array(
                    'id',
                    array(
                        'name' => 'urgent',
                        'value' => $order_request->urgent == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
                    ),
                    array(
                        'name' => 'activity_type_id',

                        'value' => $order_request->activityType ? $order_request->activityType->title : "",
                    ),


                    array(
                        'name' => 'location_id',

                        'value' => $order_request->location ? $order_request->location->title : "",
                    ),
                    'load_list',
                    array(
                        'name' => Yii::t('app', 'Paletts'),
                        'value' => $order_request->totalPaletts,
                    ),

                    'created_dt',
                    'updated_dt',

                ),
            )); ?>

        </div>

        <div class="col-md-6">
            <details open>
                <summary><?= Yii::t('app', 'Products'); ?> <span class="bs-caret"><span class="caret"></span></span>
                </summary>

                <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
                    'id' => 'order-product-grid',
                    'dataProvider' => $order_products->perOrder(),
                    'summaryText' => false,

                    'filter' => null,
                    'extraRowColumns' => array('order_client_id'),
                    'extraRowExpression' => '"<strong>".$data->orderClient->client->title." - ".$data->orderClient->order_number."</strong>"',
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
                            'type' => 'raw',
                            'value' => '$data->product ? $data->product->title . " &bull; " . $data->product->product_barcode  : ""',
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
            </details>
        </div>


    </div>
<?php endif; ?>

    <p></p>
    <h4><?= Yii::t('app', 'Controlled'); ?></h4>
    <hr>
    <div class="row">

        <div class="col-md-12">
            <?php $this->widget('ext.groupgridview.BootGroupGridView', array(
                'id' => 'order-product-grid',
                'dataProvider' => $activity_order_controls->search(),
                'summaryText' => false,
                'rowCssClassExpression' => function ($index, $data) {

                    $class = '';

                    if ($data->getControlledQuantity() > 0) {
                        if ($data->getControlledQuantity() == $data->quantity) {
                            $class .= 'success';
                        } else if ($data->getControlledQuantity() > $data->quantity) {
                            $class .= 'danger';
                        } else {
                            $class .= 'warning';
                        }
                    }


                    return $class;
                },
                'filter' => null,
                'extraRowColumns' => array('activity_order_id'),
                'extraRowExpression' => '"<strong>".$data->activityOrder->client->title." - ".$data->activityOrder->order_number."</strong>"',
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
                        'type' => 'raw',
                        'value' => '$data->product ? $data->product->title : ""',
                    ),
                    array(
                        'header' => Yii::t('app', 'Product Barcode'),
                        'type' => 'raw',
                        'value' => '$data->product ? $data->product->product_barcode  : ""',
                    ),

                    array(
                        'name' => 'quantity',
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'text-right'),
                        'headerHtmlOptions' => array('class' => 'text-right'),
                    ),

                    array(
                        'header' => 'Kontrolisano',
                        'value' => '$data->getControlledQuantity()',
                        'htmlOptions' => array('class' => 'text-right'),
                        'headerHtmlOptions' => array('class' => 'text-right'),
                    ),


                    array(
                        'header' => 'Nedostaje',
                        'type' => 'raw',
                        'value' => function ($data) {
                            return $data->quantity - $data->getControlledQuantity();
                        },
                        'htmlOptions' => array('class' => 'text-right'),
                        'headerHtmlOptions' => array('class' => 'text-right'),
                    ),


                ),
            )); ?>
        </div>
    </div>
<?php

Yii::app()->clientScript->registerCss('label_th_width', 'table.detail-view th { width:200px; }');
?>