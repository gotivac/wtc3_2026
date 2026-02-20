<h4 class="col-xs-9 text-left">
    <?= $order->order_number;?> &bull; <?=$order->client->title;?>
</h4>
<h4 class="col-xs-3 text-right">
    <a href="<?=Yii::app()->createUrl("/outbound/load/".$order->id);?>" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-arrow-left"></i></a>
</h4>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function($index,$data) {

        $class = '';
        if ($data->isLoaded()) {
            $class .= 'alert-success';
        }



        return $class;

    },

    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize)',
            'htmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
            'headerHtmlOptions' => array('class' => 'text-right','style'=>'width:60px'),
        ),

        array(
            'name' => 'sscc',
            'type' => 'raw',
            'value' => 'CHtml::link($data->sscc,Yii::app()->createUrl("/activityPalettHasProduct/view/".$data->id))',
        ),

        array(
            'header' => 'Utovar',
            'type' => 'raw',
            'value' => '$data->isLoaded() ? "<i class=\"glyphicon glyphicon-ok\"></i>" : ""',
            'headerHtmlOptions'=>array('class' => 'text-center'),
            'htmlOptions'=>array('class' => 'text-center'),
        )

    ),
));
?>