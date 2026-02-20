<h5>
    <div class="text-left col-xs-2">
        <a class="btn btn-success btn-xs" href="<?=Yii::app()->createUrl('/orderControl/palettControl/'.$activity_order->id);?>"><i class="glyphicon glyphicon-th-large"></i></a>

    </div>
    <div class="text-left col-xs-6">
        <b><?= $activity_order->order_number;?></b>
    </div>
    <div class="text-right col-xs-4">
        <a class="btn btn-warning btn-xs" href="<?=Yii::app()->createUrl('/orderControl/productControlView/'.$activity_order->id);?>"><i class="glyphicon glyphicon-eye-open"></i></a>
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/orderControl/product');?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>

<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'order-control-form',
    'type'=> 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

    <div class="col-xs-9">
        <?php echo $form->textFieldGroup($activity_order_control, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
    </div>
    <div class="col-xs-3 text-left">


        <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

    </div>
<?php $this->endWidget(); ?>
<div class="clearfix"></div>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function($index,$data) {

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
            'value' => function($data) {
                return $data->quantity - $data->getControlledQuantity();
            },
            'headerHtmlOptions'=>array('class' => 'text-center'),
            'htmlOptions'=>array('class' => 'text-center vertical-center h4'),
        )

    ),
));
?>

