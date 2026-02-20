<div class="row">
<h5>
    <div class="col-xs-10 text-left"><b>POPIS - </b> <?= $activity_palett->sscc; ?></div>

        <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/inventory/slocContent/" . $activity_palett->inSloc->sloc_id), array('class' => 'btn btn-primary btn-xs')); ?></div>

</h5>
</div>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'product-grid',
    'dataProvider' => $model->search(),
    'summaryText' => false,



    'columns' => array(

        array(
            'name' => 'product_id',
            'type' => 'raw',
            'value' => '($data->product) ? CHtml::link("<b>".$data->product->product_barcode."</b><br>".$data->product->title,Yii::app()->createUrl("/inventory/updateProduct/" . $data->id)) : ""',

        ),

        array(
            'name' => 'quantity',
            'type' => 'raw',
            'value' => '"<b>".$data->content["quantity"]."</b><br>P: ".$data->content["packages"] . "<br>K: " . $data->content["units"]',
            'headerHtmlOptions' => array('class' => 'text-right'),
            'htmlOptions' => array('class' => 'text-right'),


        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap', 'class' => 'text-right'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(

                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'url' => 'Yii::app()->createUrl("inventory/ajaxDeleteProduct/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs delete',

                    ),


                )
            ),
        ),
    ),
));
?>

<hr>
<a class="btn btn-success" href="<?= Yii::app()->createUrl('/inventory/createProductOnSSCC/' . $activity_palett->id);?>"><i class="glyphicon glyphicon-plus"></i></a>

