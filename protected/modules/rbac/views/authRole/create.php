<?php
$this->breadcrumbs=array(
	Yii::t('app','Auth Roles')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
array('label'=>Yii::t('app','List'),'url'=>array('index')),

);
?>


<div class="alert-placeholder"></div>
<div class="col-md-6 col-md-offset-3">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
