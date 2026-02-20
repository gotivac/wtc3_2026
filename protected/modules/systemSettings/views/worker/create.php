<?php
$this->breadcrumbs=array(
	Yii::t('app','Workers')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),

);
?>


<div class="alert-placeholder"></div>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>