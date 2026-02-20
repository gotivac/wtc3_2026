<?php
$this->breadcrumbs=array(
	Yii::t('app','Products')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
	array('label'=>Yii::t('app','List'),'url'=>array('index')),
	array('label'=>Yii::t('app','Create'),'url'=>array('create')),
        );
	?>
<div class="alert-placeholder"><?php
$this->widget('booster.widgets.TbAlert', array(
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array(),
    'userComponentId' => 'user',
    'alerts' => array( // configurations per alert type
        // success, info, warning, error or danger
        'success' => array('closeText' => '&times;'),
        'info', // you don't need to specify full config
        'warning' => array('closeText' => false),
        'error' => array('closeText' => Yii::t('app','Error')),
    ),
));
?>
</div>
<?php

$this->widget(
    'booster.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(
            array(
                'label' => Yii::t('app', 'Update'),
                'content' => $this->renderPartial('_form', array('model' => $model,'package_ids' => $package_ids), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0)) ? true : false,
                // 'url' => '?tab=0',

            ),

            array(
                'label' => Yii::t('app', 'Children'),
                'content' => $this->renderPartial('_children', array('model' => $model,'product_has_children' => $product_has_children, 'product_has_child'=>$product_has_child), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1) ? true : false,
                'visible' => $model->productType && ($model->productType->has_children == 1)


            ),






        ),
    )
);

?>
