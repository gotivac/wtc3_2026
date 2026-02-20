<?php
$this->breadcrumbs=array(
	Yii::t('app','Products')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),

);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model, 'package_ids' => $package_ids)); ?>