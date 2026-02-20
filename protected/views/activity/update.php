<?php
$this->breadcrumbs=array(
    Yii::t('app','Activities')=>array('index'),
    $model->activityType->title . ' - ' . $model->location->title . ' - ' . $model->gate->title=>array('update','id'=>$model->id),
    Yii::t('app','Update'),
);

$this->menu=array(
    array('label'=>Yii::t('app','Back'),'url'=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
    array('label' => Yii::t('app', 'View'), 'url' => array('view', 'id' => $model->id)),
    array('label'=>Yii::t('app','Add Order'),'url'=>'','linkOptions'=>array('onclick' => '$("#addActivityOrder").modal("show");')),
    array('label'=>Yii::t('app','Add Paletts'),'url'=>'','linkOptions'=>array('onclick' => '$("#addPaletts").modal("show");')),

    array('label' => '<i class="glyphicon glyphicon-barcode"></i> ' . Yii::t('app', 'Stickers'), 'url' => array('activity/resStickers/'.$model->id),'encodeLabel'=>false),

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
                'label' => Yii::t('app', 'Orders'),
                'content' => $this->renderPartial('_orders',array('model'=>$model,'activity_orders'=>$activity_orders,'activity_order' => $activity_order),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1 || $activity_order->hasErrors()) ? true : false,


            ),

            array(
                'label' => Yii::t('app', 'Paletts'),
                'content' => $this->renderPartial('_paletts',array('model'=>$model,'activity_paletts'=>$activity_paletts),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 2 || $activity_paletts->hasErrors()) ? true : false,


            ),
            array(
                'label' => Yii::t('app', 'Documents'),
                'content' => $this->renderPartial('_documents',array('model'=>$model,'activity_attachments'=>$activity_attachments),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 3 || $activity_attachments->hasErrors()) ? true : false,
                'visible' => count($activity_attachments->search()->getData()) > 0,


            ),

        ),
    )
);

?>

<?php
/**
 *
 *
 *          NEW ORDER MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "addActivityOrder",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title1"><?= Yii::t('app','Add Order');?></h4>
</div>
<div class="modal-body" id="activity-details-view">
    <?php $this->renderPartial('_order',array('model'=>$model,'activity_order'=>$activity_order));?>
</div>

<?php $this->endWidget(); ?>

<?php
/**
 *
 *
 *          Add Palets MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "addPaletts",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title2"><?= Yii::t('app','Add Paletts');?></h4>
</div>
<div class="modal-body" id="add-paletts-view">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'add-paletts-form',
        'type' => 'vertical',
        'enableAjaxValidation' => false,
    )); ?>

    <div class="form-group">
        <label class="control-label"><?= Yii::t('app','Order');?></label>
        <?= CHtml::dropDownList('AddPaletts[activity_order_id]','',CHtml::listData(ActivityOrder::model()->findAllByAttributes(array('activity_id'=>$model->id)),'id','order_number'),array('class'=>'form-control selectpicker'));?>
    </div>
    <div class="form-group">
        <label class="control-label"><?= Yii::t('app','Palett Count');?></label>
        <?= CHtml::textField('AddPaletts[palett_count]',1,array('class'=>'form-control'));?>
    </div>


    <div class="form-actions">
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' =>Yii::t('app', 'Add'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>

<?php $this->endWidget(); ?>
<script>
    $(document).ready(function(){
        $('#add-paletts-form').on('submit',function(){
            let count = $('#PalettCount').val();
            if (count > 9) {
                if (!confirm("Da li zaista želite da dodate " + count + " paleta?")) {
                    return false;
                }

            }

        })
    });
</script>


