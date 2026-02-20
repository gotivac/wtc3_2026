<?php
$this->breadcrumbs=array(
	Yii::t('app','SLOC Content')=>array('index'),
	$model->sloc_code,
);

$this->menu=array(
array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),

);
?>

<div class="alert-placeholder"></div>
<div class="col-md-4">

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'type' => 'bordered',
'attributes'=>array(

		'sscc',
		'sloc_code',

		'created_dt',
        array(
                'name' => 'created_user_id',
                'value' => $model->createdUserName,
        ),

		'updated_dt',
    array(
        'name' => 'updated_user_id',
        'value' => $model->updatedUserName,
    ),
),
)); ?>
</div>
<div class="col-md-8">
    <details open>
        <summary><?=Yii::t('app','Products');?> <span class="bs-caret"><span class="caret"></span></span></summary>


    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'activity-palett-grid',
        'dataProvider' => $activity_palett_has_products->search(),
        'summaryText' => false,

        'filter' => null,
        'columns' => array(
            array(
                'header' => Yii::t('app', 'Product'),
                'value' => '$data->product ? $data->product->internal_product_number. " - " . $data->product->title : ""'
            ),
            array(
                'header' => Yii::t('app', 'Product Barcode'),
                'value' => '$data->product ? $data->product->product_barcode : ""'
            ),
            array(
                'header' => Yii::t('app','Accepted'),
                'value' => '$data->quantity',

                'htmlOptions' => array('class'=>'text-right'),
                'headerHtmlOptions' => array('class'=>'text-right'),
            ),
            array(
                'header' => Yii::t('app','Delivered'),
                'value' => '$data->quantity-$data->stockQuantity',

                'htmlOptions' => array('class'=>'text-right'),
                'headerHtmlOptions' => array('class'=>'text-right'),
            ),
            array(
                'header' => Yii::t('app','Remains'),
                'value' => '$data->content["quantity"]',

                'htmlOptions' => array('class'=>'text-right'),
                'headerHtmlOptions' => array('class'=>'text-right'),
            ),
            array(
                'header' => Yii::t('app','Packages'),
                'value' => '$data->content["packages"]',

                'htmlOptions' => array('class'=>'text-right'),
                'headerHtmlOptions' => array('class'=>'text-right'),
            ),
            array(
                'header' => Yii::t('app','Units'),
                'value' => '$data->content["units"]',

                'htmlOptions' => array('class'=>'text-right'),
                'headerHtmlOptions' => array('class'=>'text-right'),
            ),


        ),
    )); ?>
    </details>
</div>