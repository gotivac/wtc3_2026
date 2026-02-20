<h4>
    <div class="col-xs-10 text-left"><?= $activity->gate->title;; ?></div>
    <div class="text-right col-xs-2"><?= CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>', Yii::app()->createUrl("/inbound/locate/" . $activity->id), array('class' => 'btn btn-primary btn-xs')); ?></div>
</h4>
<hr>
<?php

$this->widget('booster.widgets.TbGridView', array(
    'id' => 'gate-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function($index,$data) {

        $class = '';
        if ($data->isLocated()) {
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
                'header' => 'Prijem',
                'type' => 'raw',
            'value' => '(count($data->hasProducts)>0) ? "<i class=\"glyphicon glyphicon-ok\"></i>" : ""',
            'headerHtmlOptions'=>array('class' => 'text-center'),
            'htmlOptions'=>array('class' => 'text-center'),
        )

    ),
));
?>