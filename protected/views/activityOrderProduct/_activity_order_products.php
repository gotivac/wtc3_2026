<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'activity-order-product-grid',
    'dataProvider' => $activity_order_products->search(),
    'summaryText' => false,
    'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => null,
    'columns' => array(
        array(
            'header' => 'R.Br.',
            'value' => '($row + ($this->grid->dataProvider->pagination->currentPage  * $this->grid->dataProvider->pagination->pageSize) +1)."."',
            'htmlOptions' => array(
                'class' => 'text-right'
            )
        ),
        array(
            'name' => 'product_id',
            'type' => 'raw',
            'value' => '$data->product ? $data->product->title . " &bull; " . $data->product->product_barcode  : ""',
        ),
        array(
            'name' => 'package_id',
            'value' => '$data->package ? $data->package->title : ""',
        ),
        array(
            'name' => 'products_in_package',
            'type' => 'raw',
            'class' => 'application.extensions.editable.EditableColumn',
            'value' => '$data->products_in_package',
            'editable' => array(
                'title' => Yii::t('app', 'Products In Package'),
                'type' => 'text',
                'url' => $this->createUrl('activityOrderProduct/ajaxUpdata'),
                'success' => 'function(){location.href=location.href+"?tab=1";}'
            ),
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'name' => 'packages_on_palett',
            'type' => 'raw',
            'class' => 'application.extensions.editable.EditableColumn',
            'value' => '$data->packages_on_palett',
            'editable' => array(
                'title' => Yii::t('app', 'Packages On Palett'),
                'type' => 'text',
                'url' => $this->createUrl('activityOrderProduct/ajaxUpdata'),
                'success' => 'function(){location.href=location.href+"?tab=1";}'
            ),
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),
        array(
            'name' => 'products_on_palett',
            'type' => 'raw',
            'class' => 'application.extensions.editable.EditableColumn',
            'value' => '$data->products_on_palett',
            'editable' => array(
                'title' => Yii::t('app', 'Products On Palett'),
                'type' => 'text',
                'url' => $this->createUrl('activityOrderProduct/ajaxUpdata'),
                'success' => 'function(){location.href=location.href+"?tab=1";}'
            ),
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),

        array(
            'name' => 'quantity',
            'type' => 'raw',
            'value' => '$data->quantity',
            /*
            'class' => 'application.extensions.editable.EditableColumn',
            'editable' => array(
                'title' => Yii::t('app', 'Quantity'),
                'type' => 'text',
                'url' => $this->createUrl('activityOrderProduct/ajaxUpdata'),
                'success' => 'function(data){if (data!="ok") {alert(data)} location.href=location.href+"?tab=1";}'
            ),
            */
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),

        array(
            'name' => 'paletts',
            'type' => 'raw',
            'value' => '$data->paletts',
            /*
            'class' => 'application.extensions.editable.EditableColumn',
            'editable' => array(
                'title' => Yii::t('app', 'Paletts'),
                'type' => 'text',
                'url' => $this->createUrl('activityOrderProduct/ajaxUpdata'),
                'success' => 'function(data){if (data!="ok") {alert(data)} location.href=location.href+"?tab=1";}'
            ),
            */
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-right'),
        ),

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(

                'paletts' => array(
                    'label' => '<i class="glyphicon glyphicon-barcode"></i>',
                    'url' => 'Yii::app()->createUrl("/activityOrderProduct/resStickers/".$data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Stickers'),
                    )
                ),
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),

                )
            ),
        ),
    ),
)); ?>