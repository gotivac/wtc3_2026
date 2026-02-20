<h4>
    <div class="col-xs-10 text-left"><?= $activity_palett->sscc; ?></div>
    <?php if ($activity_palett->activity->direction == 'in'):;?>
    <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', count($activity_palett->hasProducts) > 0 ? Yii::app()->createUrl("/activityPalettHasProduct/update/" . $activity_palett->hasProducts[0]->id) : Yii::app()->createUrl("/activityPalettHasProduct/create/" . $activity_palett->activity_id), array('class' => 'btn btn-primary btn-xs')); ?></div>
    <?php else : ?>
        <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/outbound/gateout/" . $activity_palett->activityOrder->id), array('class' => 'btn btn-primary btn-xs')); ?></div>
    <?php endif; ?>
</h4>
<hr>
<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'afterAjaxUpdate' => 'function(){location.href=location.href}',


    'columns' => array(

        array(
            'name' => 'product_id',
            'type' => 'raw',
            'value' => '($data->product) ? "<b>".$data->product->product_barcode."</b><br>".$data->product->title."<br>".$data->delivery_number : ""',

        ),

        array(
            'name' => 'quantity',
            'type' => 'raw',
            'value' => '"<b>".$data->quantity."</b><br>P: ".$data->packages . "<br>K: " . $data->units',
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
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => '$data->activityPalett->activityOrder->activity->direction == "in"'

                )
            ),
        ),
    ),
));
?>

