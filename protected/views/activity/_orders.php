
<?php if (count($activity_orders->search()->getData()) > 0) : ?>
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'activity-order-grid',
        'dataProvider' => $activity_orders->search(),
        'summaryText' => false,
        'afterAjaxUpdate' => 'function(id,data){location.href=location.href;}',
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
                    if (!empty($data->activityOrderProducts)) {
                        $result = '<ol>';
                        foreach ($data->activityOrderProducts as $orderProduct) {
                            $result .= '<li>' . $orderProduct->product->title . ': ' . $orderProduct->quantity.'</li>';
                        }
                        $result .= '</ol>';
                    } else {
                        $result = CHtml::link(Yii::t('app','Add Products'),'/activityOrderProduct/create/'.$data->id);
                    }
                    return $result;
                },
            ),

            array(
                'header' => 'Status',
                'type' => 'raw',
                'value' => '($data->status == 1 ? "ZATVOREN" : "<a onclick=\"closeOrder(".$data->id.")\">Zatvori</a>") . "<br>" . nl2br($data->notes)',

            ),

            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{delivery_note_cut} {delivery_note} {stickers} {product} {delete}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(

                    'delivery_note_cut' => array(
                        'label' => '<i class="fa fa-scissors"></i>',
                        'url' => 'Yii::app()->createUrl("activityOrder",array("resDeliveryNoticeCut"=>$data->id))',
                        'options' => array(
                            'class' => 'btn btn-xs update',
                            'title' => Yii::t('app', 'Delivery Notice Cut'),
                        ),

                        'visible' => '$data->activity->direction == "out"',
                    ),
                    'delivery_note' => array(
                        'label' => '<i class="glyphicon glyphicon-print"></i>',
                        'url' => 'Yii::app()->createUrl("activityOrder",array("resDeliveryNote"=>$data->id))',
                        'options' => array(
                            'class' => 'btn btn-xs update',
                            'title' => Yii::t('app', 'Delivery Note'),
                        ),

                        'visible' => '$data->activity->direction == "out"',
                    ),
                    'stickers' => array(
                        'label' => '<i class="glyphicon glyphicon-barcode"></i>',
                        'url' => 'Yii::app()->createUrl("/activityOrder/resStickers/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs view',
                            'title' => Yii::t('app', 'Stickers'),
                        )
                    ),
                    'product' => array(
                        'label' => '<i class="fa fa-dropbox"></i>',
                        'url' => 'Yii::app()->createUrl("activityOrderProduct/create/".$data->id)',
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
                        'url' => 'Yii::app()->createUrl("activity/ajaxDeleteOrder/".$data->id)',
                        'options' => array(
                            'class' => 'btn btn-xs delete',

                        ),


                    )
                ),
            ),
        ),
    )); ?>

<?php endif; ?>


<?php


$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "closeOrder",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title1"><?= Yii::t('app', 'Close Order'); ?></h4>
</div>
<div class="modal-body">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'close-order-form',
        'type' => 'vertical',
        'enableAjaxValidation' => false,
    )); ?>


    <?php echo CHtml::label(Yii::t('app','Reason'), '', array('class'=>'control-label')); ?>
    <?php echo CHtml::textArea('CloseOrder[notes]', '', array('rows' => '4','class'=>'form-control')); ?>
    <?php echo CHtml::hiddenField('CloseOrder[activity_order_id]', ''); ?>

        <div class="form-actions">
            <?php $this->widget('booster.widgets.TbButton', array(
                'buttonType'=>'submit',
                'context'=>'primary',
                'label' => Yii::t('app', 'Save'),
            )); ?>
        </div>


    <?php $this->endWidget(); ?>

</div>

<?php $this->endWidget(); ?>


<script>
    function closeOrder(id)
    {
        <?php if (!$this->user->isAdmin()):?>
        return false;
        <?php endif;?>
        $('#CloseOrder_activity_order_id').val(id);
       $('#closeOrder').modal('show');
    }

</script>