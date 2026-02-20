<h5>
    <div class="text-left col-xs-2">
        <a class="btn btn-success btn-xs" href="<?=Yii::app()->createUrl('/orderControl/productControl/'.$activity_order->id);?>"><i class="glyphicon glyphicon-pause"></i></a>

    </div>
    <div class="text-left col-xs-6">
        <b><?= $activity_order->order_number; ?></b>
    </div>
    <div class="text-right col-xs-4">
        <a class="btn btn-warning btn-xs"
           href="<?= Yii::app()->createUrl('/orderControl/productControlView/' . $activity_order->id); ?>"><i
                    class="glyphicon glyphicon-eye-open"></i></a>
        <a class="btn btn-primary btn-xs" href="<?= Yii::app()->createUrl('/orderControl/product'); ?>"><i
                    class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>

<div class="clearfix"></div>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'order-control-form',
    'type' => 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

<div class="col-xs-9">


    <?php echo $form->textFieldGroup($activity_order_control, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip','tabindex'=>"1" )), 'labelOptions' => array('label' => false))); ?>
</div>
<div class="col-xs-3 text-left">

    <button tabindex="5" type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

</div>

<div class="col-xs-4">
    <?php echo $form->textFieldGroup($activity_order_control, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip','tabindex'=>"2")), 'labelOptions' => array('label' => false))); ?>
</div>
<div class="col-xs-4">
    <?php echo $form->textFieldGroup($activity_order_control, 'packages', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip','tabindex'=>"3")), 'labelOptions' => array('label' => false))); ?>
</div>
<div class="col-xs-4">
    <?php echo $form->textFieldGroup($activity_order_control, 'units', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip','tabindex'=>"4")), 'labelOptions' => array('label' => false))); ?>
</div>


<?php $this->endWidget(); ?>
<div class="clearfix"></div>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function ($index, $data) {

        $class = '';
        if ($data->getControlledQuantity() > 0) {
            if ($data->getControlledQuantity() == $data->quantity) {
                $class .= 'alert-success';
            } else if ($data->getControlledQuantity() > $data->quantity) {
                $class .= 'alert-danger';
            } else {
                $class .= 'alert-warning';
            }
        }


        return $class;

    },

    'columns' => array(
        array(
            'name' => 'product_id',
            'type' => 'raw',
            'value' => '$data->product->product_barcode."<br>".$data->product->title',
        ),

        array(
            'header' => 'Količina',
            'type' => 'raw',
            'value' => function ($data) {
                return $data->quantity - $data->getControlledQuantity();
            },
            'headerHtmlOptions' => array('class' => 'text-center'),
            'htmlOptions' => array('class' => 'text-center vertical-center h4'),
        )

    ),
));
?>

<script>
    $('#ActivityOrderControl_product_barcode').on('change',function(){
        let productBarcode = $(this).val();
        let activityOrderId = '<?= $activity_order->id;?>';
        $.ajax({
            url: '<?=Yii::app()->createUrl("/orderControl/ajaxGetProductQuantity");?>',
            data: {
                'product_barcode' : productBarcode,
                'activity_order_id' : activityOrderId,
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                $('#ActivityOrderControl_quantity').val(data.quantity);
                $('#ActivityOrderControl_packages').val(data.packages);
                $('#ActivityOrderControl_units').val(data.units);
            }
        });
    });
</script>