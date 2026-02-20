<?php
$this->breadcrumbs=array(
	Yii::t('app','Activities')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','Back'),'url'=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('order/index')),

);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>