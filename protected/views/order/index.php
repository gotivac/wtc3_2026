<?php

if (isset($_GET['filter'])) {
    switch ($_GET['filter']) {
        case 'yellow':
            $guide = 'Primljeni';
            break;
        case 'red':
            $guide = 'Spremni';
            break;
        case 'green':
            $guide = 'U obradi';
            break;
        default:
            $guide = Yii::t('app', 'List');
            break;

    }
} else {
    $guide = Yii::t('app', 'List');
}

$this->breadcrumbs = array(
    Yii::t('app', 'Order Requests') => array('index'),
    $guide
);
$this->menu = array(

    array('label' => Yii::t('app', 'Create'), 'url' => array('create'), 'visible' => $this->user->canCreate('order') == null ? false : true),

);

?>
<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('order-request-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>
<div class="search-form">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div>
<div class="col-md-10 text-left float-left ">

    <div class="bg-warning col-md-2 text-center" style="padding: 8px;"><?= Yii::t('app', 'Time Slot Ready'); ?></div>
    <div class="bg-danger col-md-2 text-center"
         style="padding: 8px;"><?= Yii::t('app', 'Activity In Progress'); ?></div>
    <div class="bg-success col-md-2 text-center" style="padding: 8px;"><?= Yii::t('app', 'Order Completed'); ?></div>
</div>
<div class="col-md-2 text-right float-right">
    <a class="btn btn-<?= isset($_GET['filter']) && $_GET['filter'] == 'yellow' ? "small" : "xs"; ?> btn-warning"
       title="Primljeni" href="?filter=yellow"><i class="fa fa-truck"></i></a>

    <a class="btn btn-<?= isset($_GET['filter']) && $_GET['filter'] == 'green' ? "small" : "xs"; ?> btn-success"
       title="U obradi" href="?filter=green"><i class="fa fa-truck"></i></a>
    <a class="btn btn-<?= isset($_GET['filter']) && $_GET['filter'] == 'red' ? "small" : "xs"; ?> btn-danger"
       title="Završeni" href="?filter=red"><i class="fa fa-truck"></i></a>
    <a class="btn btn-xs view" title="Svi" href="?filter=false"><i class="fa fa-truck"></i></a>

</div>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'order-request-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => null,
    'rowCssClassExpression' => function ($index, $data) {

        $class = '';

        if ($data->activity) {
            if ($data->activity->truck_dispatch_datetime == null) {
                $class = 'danger';
            } else {
                $class = 'success';
            }
        } else {
            if (!$data->timeSlot) {
                $class = 'warning';
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
            'header' => Yii::t('app', 'Client') . " &bull; " . Yii::t('app', 'Order Number') . ' &bull; ' . Yii::t('app', 'Customer | Supplier'),
            'name' => 'order_number',
            'type' => 'raw',

            'value' => function ($data) {
                switch (count($data->orderClients)) {
                    case 0:
                        return '';

                    case 1:
                        return $data->orderClients[0]->client->title . " &bull; " . $data->orderClients[0]->order_number . " &bull; " . $data->orderClients[0]->customerSupplier->title;

                    default:
                        $res = '';
                        foreach ($data->orderClients as $order_client) {
                            $res .= $order_client->client->title . " &bull; " . $order_client->order_number . " &bull; " . $order_client->customerSupplier->title . '<br>';
                        }

                        return rtrim($res, '<br>');

                }
            }
        ),
        array(
            'name' => 'activity_type_id',
            'value' => '$data->activityType ? $data->activityType->title : ""',
            'filter' => CHtml::listData($this->user->allowedActivityTypes, 'id', 'title'),
        ),

        array(
            'name' => 'location_id',
            'value' => '$data->location ? $data->location->title : ""',
            'filter' => CHtml::listData(Location::model()->findAll(), 'id', 'title'),
            'visible' => !$this->user->location,
        ),

        'load_list',
        array(
            'header' => Yii::t('app', 'Delivery Type'),
            'name' => 'delivery_type_search',
            'type' => 'raw',

            'value' => function ($data) {
                switch (count($data->orderClients)) {
                    case 0:
                        return '';

                    case 1:
                        return $data->orderClients[0]->delivery_type;

                    default:
                        $res = '';
                        foreach ($data->orderClients as $order_client) {
                            $res .= $order_client->delivery_type . '<br>';
                        }

                        return rtrim($res, '<br>');

                }
            }
        ),
        array(
            'header' => Yii::t('app', 'Created'),
            'value' => 'date("d.m.Y H:i",strtotime($data->created_dt))',
        ),
        array(
            'header' => Yii::t('app', 'Broj stavki'),
            'type' => 'raw',
            'value' => '$data->totalRows',


            'htmlOptions' => array('class' => 'text-right'),
            'footerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'header' => 'Traženo komada',
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';

                foreach ($data->orderClients as $order_client) {
                    $quantity = 0;
                    foreach ($order_client->orderProducts as $order_product) {
                        $quantity += $order_product->quantity;
                    }
                    $result .= number_format($quantity, 0, ',', '.') . '<br>';

                }
                return $result;
            },
            'footer' => number_format($total,0,',','.'),

            'htmlOptions' => array('class' => 'text-right'),
            'footerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'header' => Yii::t('app', 'Finished'),
            'value' => '$data->activity && $data->activity->system_acceptance_datetime != NULL ? date("d.m.Y H:i",strtotime($data->activity->system_acceptance_datetime)) : ""',
        ),
        array(
            'header' => 'Isporučeno komada',
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';
                if ($data->activity) {

                    if ($data->activity->isReady() || $data->activity->truck_dispatch_datetime != NULL) {
                        foreach ($data->orderClients as $order_client) {
                            $quantity = 0;
                            $activity_order = ActivityOrder::model()->findByAttributes(array('order_client_id' => $order_client->id));
                            if ($activity_order) {
                                foreach ($activity_order->activityPaletts as $activity_palett) {
                                    foreach ($activity_palett->hasProducts as $activity_palett_has_product) {
                                        $quantity += $activity_palett_has_product->quantity;

                                    }
                                }
                            }
                            $result .= number_format($quantity, 0, ',', '.') . '<br>';
                        }
                    }

                }
                return $result;
            },
            'htmlOptions' => array('class' => 'text-right'),
            'footer' => number_format($completed,0,',','.'),


            'footerHtmlOptions' => array('class' => 'text-right'),
        ),

        array(
            'header' => Yii::t('app', 'Delivered'),
            'value' => '$data->activity && $data->activity->truck_dispatch_datetime != NULL ? date("d.m.Y H:i",strtotime($data->activity->truck_dispatch_datetime)) : ""',
        ),

        array(
            'header' => 'Vreme do isporuke',
            'type' => 'raw',
            'value' => function ($data) {
                $today = date_create(date('Y-m-d'));
                $deadline = date_create($data->deadline);
                $diff = date_diff($today, $deadline);
                $days = 0 + $diff->format("%R%a");

                if ($data->activity && $data->activity->system_acceptance_datetime != NULL) {
                    return '<span style="font-size:100%" class="label label-square label-default">OK</span>';
                }


                if ($days > 0) {
                    $label_class = 'label-success';
                } else if ($days == 0) {
                    $label_class = 'label-warning';
                } else {
                    return '<span style="font-size:100%" class="label label-square label-danger"><i class="glyphicon glyphicon-warning-sign"></i></span>';
                }
                return '<span style="font-size:100%" class="label label-square ' . $label_class . '"><b>' . $days . '</b></span>';


            },
            'htmlOptions' => array('class' => 'text-center'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),
        /*
                array(
                    'header' => 'Datum isporuke',
                    'type' => 'raw',
                    'value' => function ($data) {
                        $res = '';
                        foreach ($data->orderClients as $order_client) {

                            if ($order_client->delivery_date == null && $order_client->orderKlett) {

                                if ($order_client->orderKlett->DeliveryDate != NULL) {

                                    $res .= date('d.m.Y', strtotime($order_client->orderKlett->DeliveryDate)) . '<br>';
                                } else {
                                    $res .= $order_client->orderKlett->DeliveryDate . '<br>';
                                }
                            } else {
                                $res .= $order_client->delivery_date . '<br>';
                            }
                        }

                        return rtrim($res, '<br>');
                    }
                ),
        */


        array(
            'htmlOptions' => array('nowrap' => 'nowrap', 'class' => 'text-right'),
            'template' => '{labels} {activity} {time_slot} {view} {update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',

            'buttons' => array(
                'activity' => array(
                    'label' => '<i class="fa fa-truck"></i>',
                    'url' => '$data->activity ? Yii::app()->createUrl("/activity/".$data->activity->id) : Yii::app()->createUrl("/activity/create/" . $data->id)',
                    'options' => array(
                        'class' => '"btn btn-xs view activity-". ($data->activity ? ($data->activity->isReady() ? "red" : "green") : "yellow")',
                        'title' => Yii::t('app', 'Activity'),
                        'evaluateOptions' => array('class'),

                    ),
                    'visible' => 'count($data->orderClients)>0',
                ),
                'time_slot' => array(
                    'label' => '<i class="glyphicon glyphicon-calendar"></i>',
                    'options' => array(
                        'class' => '"btn btn-xs view time-slot activity-". ($data->timeSlot ? "green" : "yellow")',
                        'title' => Yii::t('app', 'Time Slot'),
                        'evaluateOptions' => array('id', 'class'),
                        'id' => '"order_".$data->id',
                    ),
                    'visible' => 'count($data->orderClients)>0 && empty($data->activity)',
                ),
                'labels' => array(
                    'label' => '<i class="glyphicon glyphicon-print"></i>',
                    'url' => 'Yii::app()->createUrl("/order/resLabels/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Labels'),
                        'evaluateOptions' => array('id'),
                        'id' => '"order_".$data->id',
                    ),
                    'visible' => '$data->activity && $data->direction == "out" && ($data->activity->isReady() || $data->activity->truck_dispatch_datetime != NULL)',
                ),
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
                    'visible' => 'empty($data->activity)',
                ),
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => 'empty($data->activity)',

                )
            ),
        ),
    ),
)); ?>



<?php
/**
 *  Additional data for creating new Time Slot based on data from order
 *
 *          NEW TIME SLOT MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "createTimeSlot",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title2"><?= Yii::t('app', 'Create Time Slot'); ?></h4>
</div>
<div class="modal-body" id="timeslot-form">
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'time-slot-form',
        'type' => 'vertical',
        'enableAjaxValidation' => false,

    ));
    ?>

    <?php echo $form->hiddenField($time_slot, 'order_request_id'); ?>
    <?php echo $form->hiddenField($time_slot, 'activity_type_id'); ?>
    <?php echo $form->dropDownListGroup($time_slot, 'truck_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(TruckType::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '')))); ?>
    <?php echo $form->textFieldGroup($time_slot, 'license_plate', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>


    <div class="form-actions">
        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('app', 'Save'), '', array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {

                        $(".error").parent().removeClass("has-error");
                     
                        
                        if (typeof data.id != "undefined") {
                            $.each(data, function(key,val) {
                                $("#TimeSlot_"+key+"_em_").remove();
                                $("#TimeSlot_"+key).parent().removeClass("has-error");
                            });
                             location.href="/timeSlot/create2/"+data.id+"?tab=2";
                        }  else {
                            $.each(data, function(key,val) {
                            if (!$("#"+key+"_em_").is(":visible")) {
                                $("#"+key).after("<div id=\""+key+"_em_"+"\" class=\"help-block error\">"+val+"</div>");
                                $("#"+key).parent().addClass("has-error");
                                }
                            }); 
                        } 
                    }',
        ), array('class' => 'btn btn-primary'));
        ?>
    </div>
</div>


<?php $this->endWidget(); ?>

<?php $this->endWidget(); ?>



<?php
/**
 *  Window for selecting time slot amongs existing
 *
 *          SELECT TIME SLOT MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "selectTimeSlot",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title3"><?= Yii::t('app', 'Select Time Slot'); ?></h4>
</div>
<div class="modal-body" id="timeslot-list">

</div>
<?php $this->endWidget(); ?>


<script>
    $(document).ready(function () {
        var modal = ($('#selectTimeSlot').children()[0]);
        modal.style.width = '1200px';

        $('.time-slot').on('click', function () {
            let orderRequestId = $(this).attr('id').split('_')[1];
            $.ajax({
                url: '<?=Yii::app()->createUrl("order/ajaxGetTimeSlot");?>',
                type: 'get',
                dataType: 'json',
                data: {'id': orderRequestId},
                success: function (data) {
                    switch (data.result) {
                        case 'create':
                            $('#TimeSlot_order_request_id').val(orderRequestId);
                            $('#TimeSlot_activity_type_id').val(data.order.activity_type_id);
                            $('#createTimeSlot').modal('show');
                            break;
                        case 'select':
                            $('#timeslot-list').html(data.content);
                            $('#selectTimeSlot').modal('show');
                            break;
                        case 'link':
                            let link = location.protocol + '//' + location.hostname + data.content;
                            window.open(link, '_blank') || window.location.replace(link);


                            break;
                        default:
                            break;
                    }
                }
            });
        });
    });

    function showCreate(orderRequestId, activityTypeId) {
        $('#selectTimeSlot').modal('hide');
        $('#TimeSlot_order_request_id').val(orderRequestId);
        $('#TimeSlot_activity_type_id').val(activityTypeId);
        $('#createTimeSlot').modal('show');

    }

    function filterYellow() {
        $.ajax({
            url: '',
            type: 'get',
            data: {'filter': 'yellow'},
            success: function () {
                // $.fn.yiiGridView.update('order-request-grid');
            }
        })
    }

    function filterRed() {
        alert('Red');
    }
</script>