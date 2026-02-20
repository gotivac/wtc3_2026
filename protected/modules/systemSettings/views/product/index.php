<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Products') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
    array('label' => Yii::t('app', 'Excel'), 'url' => array('excel')),
);

?>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'product-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(
        'title',
        'external_product_number',
        'internal_product_number',
        'product_barcode',
        array(
            'name' => 'description',
            'value'=> 'Helpers::safe_string($data->description,5)'
        ),
        array(
            'name' => 'weight',
            'htmlOptions'=>array('class'=>'col-md-1 text-right'),
        ),
        array(
            'name' => 'client_id',
            'filter' => CHtml::listData(Client::model()->findAll(array('order'=>'title')),'id','title'),
            'value' => '$data->client ? $data->client->title : ""',
            'htmlOptions'=>array('class'=>'col-md-2'),
        ),

        array(
            'name' => 'product_type_id',
            'filter' => CHtml::listData(ProductType::model()->findAll(array('order'=>'title')),'id','title'),
            'value' => '$data->productType ? $data->productType->title : ""',
            'htmlOptions'=>array('class'=>'col-md-1'),
        ),

        array(
            'name' => 'package_id',

            'value' => '$data->defaultPackage ? $data->defaultPackage->title : ""',
        ),



        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{view} {update} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs view'
                    )
                ),
                'update' => array(
                    'label' => Yii::t('app', 'Update'),
                    'options' => array(
                        'class' => 'btn btn-xs update'
                    )
                ),
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => (Yii::app()->params['adminDelete']),
                )
            ),
        ),
    ),
)); ?>
