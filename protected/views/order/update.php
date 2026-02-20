<?php
$this->breadcrumbs=array(
	Yii::t('app','Order Requests')=>array('index'),
	$model->activityType->title . ' - ' . $model->location->title . ' - ' . (is_array($model->orderNumber) ? implode('_',$model->orderNumber) : $model->orderNumber)=>array('update','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
        array('label'=>Yii::t('app','Back'),'url'=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
	array('label'=>Yii::t('app','Add Client'),'url'=>'','linkOptions'=>array('onclick' => '$("#addOrderClient").modal("show");')),
	array('label'=>'<i class="glyphicon glyphicon-calendar"></i>' . "&nbsp;" .  Yii::t('app','Time Slot'),'url'=>'','linkOptions'=>array('id' => 'order_'.$model->id,'class'=>'time-slot'),'visible' => count($model->orderClients) > 0, 'encodeLabel'=>false),
	array('label'=>'<i class="fa fa-truck"></i>' . "&nbsp;" .  Yii::t('app','Activity'),'url'=>$model->activity ? Yii::app()->createUrl("/activity/".$model->activity->id) : Yii::app()->createUrl("/activity/create/" . $model->id),'visible' => count($model->orderClients) > 0, 'encodeLabel'=>false),
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
        'error' => array('closeText' => Yii::t('app','Error')),
    ),
));
?>
</div>
<?php


$this->widget(
    'booster.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(

            array(
                'label' => Yii::t('app', 'Update'),
                'content' => $this->renderPartial('_form', array('model' => $model), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0) || $model->hasErrors()) ? true : false,
                // 'url' => '?tab=0',

            ),

            array(
                'label' => Yii::t('app', 'Clients'),
                'content' => $this->renderPartial('_clients',array('model'=>$model,'order_clients'=>$order_clients,'order_client' => $order_client),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1 || $order_client->hasErrors()) ? true : false,


            ),

        ),
    )
);

?>

<?php
/**
 *
 *
 *          NEW CLIENT MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "addOrderClient",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title1"><?= Yii::t('app','Add Client');?></h4>
</div>
<div class="modal-body" id="activity-details-view">
    <?php $this->renderPartial('_client',array('model'=>$model,'order_client'=>$order_client));?>
</div>

<?php $this->endWidget(); ?>




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
                                let link = location.protocol + '//' + location.hostname+data.content;
                                window.open(link, '_blank') || window.location.replace(link);


                                break;
                            default:
                                break;
                        }
                    }
                });
            });
        });
    </script>