<?php
$this->breadcrumbs=array(
	Yii::t('app','Clients')=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
	array('label'=>Yii::t('app','List'),'url'=>array('index')),
	array('label'=>Yii::t('app','Create'),'url'=>array('create')),
        );
	?>
<div class="alert-placeholder">
<?php
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
                'content' => $this->renderPartial('_form', array('model' => $model, 'storage_type_ids' => $storage_type_ids,'section_ids'=>$section_ids), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0)) ? true : false,
                // 'url' => '?tab=0',

            ),

            array(
                'label' => Yii::t('app', 'Buyers'),
                'content' => $this->renderPartial('_buyers', array('model' => $model,'client_has_buyers' => $client_has_buyers), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1) ? true : false,


            ),
            array(
                'label' => Yii::t('app', 'Suppliers'),
                'content' => $this->renderPartial('_suppliers', array('model' => $model,'client_has_suppliers' => $client_has_suppliers), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 2) ? true : false,


            ),





        ),
    )
);

?>

