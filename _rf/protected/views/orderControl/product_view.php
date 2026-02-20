<h5>
    <div class="text-left col-xs-8">
        <b><?= $activity_order->order_number;?></b>
    </div>
    <div class="text-right col-xs-4">
        <a class="btn btn-danger btn-xs" href="<?=Yii::app()->createUrl('/orderControl/removeControl/'.$activity_order->id);?>" onclick='return confirm("Da li ste sigurni da želite da obrišete sve?");'><i class="glyphicon glyphicon-remove"></i></a>
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/orderControl/productControl/'.$activity_order->id);?>"><i class="glyphicon glyphicon-arrow-left"></i></a>

    </div>
</h5>
<div class="clearfix"></div>
<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'order-control-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,


    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '($row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize))."."',
            'htmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
            'headerHtmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
        ),

        array(
            'name' => 'product_barcode',
            'type' => 'raw',

        ),

        array(
            'name' => 'quantity',



            'headerHtmlOptions'=>array('class' => 'text-right'),
            'htmlOptions'=>array('class' => 'text-right'),
        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap','class'=>'text-right'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(

                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),

                )
            ),
        ),

    ),
));
?>