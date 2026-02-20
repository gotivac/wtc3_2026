<?php
$this->breadcrumbs=array(
	Yii::t('app','Clients')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),

);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model ,'storage_type_ids' => $storage_type_ids,'section_ids'=>$section_ids)); ?>