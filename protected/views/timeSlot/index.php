<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Time Slots') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
);

?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'time-slot-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'rowCssClassExpression' => function($index,$data) {

        $class = '';


        if ($data->order && $data->order->activity) {
            if ($data->order->activity->truck_dispatch_datetime == null) {
                $class = 'danger';
            } else {
                $class = 'success';
            }
        } else {
            if (!$data->gate) {
                $class = 'text-muted';
            }
        }




        return $class;

    },
    'columns' => array(
        array(
                'name' => 'id',
                'htmlOptions' => array('class' => 'text-right id-column'),
                'headerHtmlOptions' => array('class' => 'text-right'),

        ),

        array(
            'name' => 'defined_date',
            'filter' => $this->widget('booster.widgets.TbDatePicker', array(
                'model' => $model,
                'attribute' => 'defined_date',
                'options' => array(
                    'format' => 'dd.mm.yyyy',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '','class'=>'form-control')
            ), true),
            'htmlOptions' => array('class' => 'col-md-1')

        ),

        array(
            'name' => 'activity_type_id',
            'filter' => CHtml::listData(ActivityType::model()->findAll(),'id','title'),
            'value' => '$data->activityType ? $data->activityType->title : ""',
        ),
        array(
            'name' => 'location_id',
            'filter' => $this->user->location ? CHtml::listData(Location::model()->findAllByAttributes(array('id'=>$this->user->location->id)),'id','title') : CHtml::listData(Location::model()->findAll(),'id','title'),
            'value' => '$data->location ? $data->location->title : ""',
        ),
        array(
            'name' => 'section_id',
            'filter' => $this->user->location ? CHtml::listData(Section::model()->findAllByAttributes(array('location_id'=>$this->user->location->id)),'id','title') : CHtml::listData(Section::model()->findAll(),'id','title'),
            'value' => '$data->section ? $data->section->title : ""',
        ),
        array(
            'name' => 'gate_id',
            'filter' => $this->user->location ? CHtml::listData(Gate::model()->findAllByAttributes(array('location_id'=>$this->user->location->id)),'id','title') : CHtml::listData(Gate::model()->findAll(),'id','title'),
            'value' => '$data->gate ? $data->gate->title : ""',
        ),

        array(
            'name' => 'start_time',
            'value' => '($data->start_time) ? date("H:i",strtotime($data->start_time)) : ""',
            'htmlOptions' => array('class' => 'text-center col-md-1'),
        ),
        array(
            'name' => 'end_time',
            'value' => '($data->end_time) ? date("H:i",strtotime($data->end_time)) : ""',
            'htmlOptions' => array('class' => 'text-center col-md-1'),
        ),
        array(
            'name' => 'truck_type_id',
            'value' => '$data->truckType ? $data->truckType->title : ""',
            'filter' => CHtml::listData(TruckType::model()->findAll(array('order' => 'title')), 'id', 'title'),
        ),

        array(
            'name' => 'license_plate',

            'htmlOptions' => array('class' => 'col-md-1'),
        ),
        array(
            'header' => Yii::t('app','Orders'),
            'type' => 'raw',
            'value' => function($data) {
                $res = '<ul  style="list-style-type:square">';
                if ($data->order) {
                    foreach ($data->order->orderClients as $order_client) {
                        $res .= '<li>'.$order_client->order_number . ': ' . date('d.m.Y H:i',strtotime($order_client->created_dt)) . '</li>';


                    }
                }
                $res .= '</ul>';
                return $res;
            },
            'headerHtmlOptions' => array('class'=>'text-center'),
            'htmlOptions' => array('class'=>'col-md-2'),
        ),
        array(
            'header' => Yii::t('app','Clients'),
            'type' => 'raw',
            'value' => function($data) {
                $res = '';
                foreach ($data->timeSlotDetails as $detail) {
                    $res .= $detail->client->title.': '.$detail->paletts;

                    $res .= '<br>';
                }

                return $res;
            },
            'headerHtmlOptions' => array('class'=>'text-center'),
            'htmlOptions' => array('class'=>'col-md-1'),
        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap','class'=>'text-right'),
            'template' => '{activity} {view} {update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs view'
                    )
                ),

                'activity' => array(
                    'label' => '<i class="fa fa-truck"></i>',
                    'url' => '$data->order && $data->order->activity ? Yii::app()->createUrl("/activity/".$data->order->activity->id) : Yii::app()->createUrl("/activity/create/" . $data->order->id)',
                    'options' => array(
                        'class' => '"btn btn-xs view activity-". ($data->order && $data->order->activity ? "green" : "yellow")',
                        'title' => Yii::t('app', 'Activity'),
                        'evaluateOptions' => array('class'),


                    ),
                    'visible' => '$data->order != null',
                ),
                'update' => array(
                    'label' => Yii::t('app', 'Update'),
                    'url' => 'Yii::app()->createUrl("timeSlot",array("update"=>$data->id)) . "?tab=2"',
                    'options' => array(
                        'class' => 'btn btn-xs update'
                    ),

                ),

                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),

                )
            ),
        ),
    ),
)); ?>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
        $('#TimeSlot_defined_date').datepicker({format:'dd.mm.yyyy',language:'rs-latin',autoclose:true});
	}"
);
?>

<script>
    function truckArrived(data)
    {
        alert(data);
    }
</script>