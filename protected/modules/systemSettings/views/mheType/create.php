<?php
$this->breadcrumbs=array(
	Yii::t('app','Mhe Types')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),

);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>