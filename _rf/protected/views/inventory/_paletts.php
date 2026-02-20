<?php
$this->widget('booster.widgets.TbGridView', array(
    'id' => 'palett-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function ($index, $data) {


        $quantity = 0;
        foreach ($data->activityPalett->hasProducts as $activity_palett_has_product) {
            $quantity += $activity_palett_has_product->realQuantity;
        }

        if ($data->activityPalett->totalRealQuantity < 0) {
            $class = 'alert-error';
        } else if ($data->activityPalett->totalRealQuantity == 0) {
            $class = 'alert-warning';
        } else {
            $class = '';
        }
        return $class;

    },

    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
* $this->grid->dataProvider->pagination->pageSize)',
            'htmlOptions' => array('class' => 'text-right', 'style' => 'width:40px'),
            'headerHtmlOptions' => array('class' => 'text-right', 'style' => 'width:40px'),
        ),

        array(
            'name' => 'sscc',
            'type' => 'raw',
            'value' => 'CHtml::link($data->sscc,Yii::app()->createUrl("/inventory/viewPalett/".$data->activityPalett->id))',
        ),
        array(
            'header' => 'Kom.',
            'value' => '$data->activityPalett->totalRealQuantity',
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap','class'=>'text-right'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'delete' => array(
                    'label' => '<i class="glyphicon glyphicon-remove"></i>',
                    'url' => 'Yii::app()->createUrl("inventory/ajaxDeletePalett/".$data->activityPalett->id)',
                    'options' => array(
                        'class' => 'btn btn-xs btn-danger',
                        'title' => 'Ukloni',
                    ),
                    'visible' => '$data->activityPalett->getTotalRealQuantity() <= 0',
                )
            ),
        ),


    ),
));
?>