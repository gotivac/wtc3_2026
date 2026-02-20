<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Activities') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(// array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>


<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'activity-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'mergeColumns' => array('truck_arrived_date'),
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'filter' => $model,

    'columns' => array(
        array(
            'name' => 'truck_arrived_date',
            'filter' => $this->widget('booster.widgets.TbDatePicker', array(
                'model' => $model,
                'attribute' => 'truck_arrived_date',
                'options' => array(
                    'format' => 'dd.mm.yyyy',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')
            ), true),
            'htmlOptions' => array('class' => 'col-md-1')
        ),


        array(
            'name' => 'location_id',
            'value' => '$data->location ? $data->location->title : ""',
            'filter' => CHtml::listData(Location::model()->findAll(), 'id', 'title'),
            'visible' => !$this->user->location,
        ),
        array(
            'name' => 'gate_id',
            'value' => '$data->gate ? $data->gate->title : ""',
            'filter' => CHtml::listData(Gate::model()->byLocation(), 'id', 'title'),
        ),

        array(
            'name' => 'activity_type_id',
            'value' => '$data->activityType ? $data->activityType->title : ""',
            'filter' => CHtml::listData($this->user->allowedActivityTypes, 'id', 'title'),
        ),


        array(
            'name' => 'truck_arrived_time',
            'value' => '($data->truck_arrived_datetime != null) ? date("H:i",strtotime($data->truck_arrived_time)) : null',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
        'license_plate',
        array(
            'name' => 'truck_dispatch_time',
            'value' => '($data->truck_dispatch_time != null) ? date("H:i",strtotime($data->truck_dispatch_time)) : ""',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
/*
        array(
            'name' => 'truck_checked',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->truck_checked) ? "<span class=\"glyphicon glyphicon-ok\"></span>" : ""',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
*/

        array(
            'name' => 'system_acceptance_datetime',
            'filter' => $this->widget('booster.widgets.TbDatePicker', array(
                'model' => $model,
                'attribute' => 'system_acceptance_datetime',
                'options' => array(
                    'format' => 'dd.mm.yyyy',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')
            ), true),
            'htmlOptions' => array('class' => 'col-md-1')
        ),
/*
        array(
            'name' => 'urgent',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->urgent) ? "<span class=\"glyphicon glyphicon-ok\"></span>" : ""',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),

        ),
*/
        'created_dt',

        array(
            'name' => 'order_number_search',
            'type' => 'raw',
            'value' => function($data) {
                $result = '';
                foreach ($data->activityOrders as $activity_order) {
                    $result .=  $activity_order->order_number . '<br>';
                }

                return $result;
            }

        ),
        array(
            'name' => 'order_id_search',
            'type' => 'raw',
            'value' => function($data) {
                $result = '';
                foreach ($data->activityOrders as $activity_order) {
                    $result .=  $activity_order->id . '<br>';
                }

                return $result;
            }

        ),


       // 'updated_dt',

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{inbound_issues} {receipt} {delivery_note} {view} {update} {delete}',
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
                    ),
                    'visible' => '$data->truck_dispatch_time == null',
                ),
                'receipt' => array(
                    'label' => '<i class="glyphicon glyphicon-print"></i>',
                    'url' => 'Yii::app()->createUrl("activity",array("resReceipt"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-xs update',
                        'title' => Yii::t('app', 'Receipt'),
                    ),

                    'visible' => '$data->direction == "in"',
                ),
                'inbound_issues' => array(
                    'label' => '<i class="glyphicon glyphicon-warning-sign"></i>',
                    'url' => 'Yii::app()->createUrl("activity",array("resInboundIssues"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-xs  update alert-warning',
                        'title' => Yii::t('app', 'Inbound Issues'),
                    ),

                    'visible' => '$data->inboundIssues()',
                ),
                'delivery_note' => array(
                    'label' => '<i class="glyphicon glyphicon-print"></i>',
                    'url' => 'Yii::app()->createUrl("activity",array("resDeliveryNote"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-xs update',
                        'title' => Yii::t('app', 'Delivery Note'),
                    ),

                    'visible' => '$data->direction == "out"',
                ),


                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => '$data->truck_dispatch_time == null',
                    // 'visible' => 'count($data->located) == 0',

                )
            ),
        ),
    ),
)); ?>
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
        $('#Activity_truck_arrived_date').datepicker({format:'dd.mm.yyyy',language:'rs-latin',autoclose:true});
	}"
);
?>