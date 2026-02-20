<?php
$this->widget('booster.widgets.TbGridView', array(
    'id' => 'product-grid',
    'enableSorting' => false,
    'dataProvider' => $model->search(),
    'summaryText' => false,
    'rowCssClassExpression' => function ($index, $data) {
        $class = '';
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
            'name' => 'product_id',
            'type' => 'raw',
            'value' => '($data->product) ? CHtml::link("<b>".$data->product->product_barcode."</b><br>".$data->product->title,Yii::app()->createUrl("/inventory/updateSlocProduct/" . $data->id)) : ""',

        ),
        array(
            'header' => 'Kom.',
            'value' => '$data->realQuantity',
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap', 'class' => 'text-right'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'delete' => array(
                    'label' => '<i class="glyphicon glyphicon-remove"></i>',
                    'url' => 'Yii::app()->createUrl("inventory/ajaxDeleteSlocProduct/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs btn-danger',
                        'title' => 'Ukloni',
                    ),

                )
            ),
        ),


    ),
));
?>


<hr>
<a class="btn btn-success" href="<?= Yii::app()->createUrl('/inventory/createProductOnSloc/' . $model->sloc_id);?>"><i class="glyphicon glyphicon-plus"></i></a>

