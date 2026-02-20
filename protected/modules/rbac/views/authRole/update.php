<?php
$this->breadcrumbs=array(
	Yii::t('app','Auth Roles')=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
	array('label'=>Yii::t('app','List'),'url'=>array('index')),
	array('label'=>Yii::t('app','Create'),'url'=>array('create')),
        array('label'=>Yii::t('app','Update'),'url'=>array('update','id'=>$model->id),'active'=>true),
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

<div class="col-md-2">
    <h4><?= Yii::t('app','Role');?></h4>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>



</div>
    <div class="col-md-8 col-sm-12">
        <h4><?= Yii::t('app','Controllers');?></h4>
<?php echo $this->renderPartial('_rights',array('model'=>$model)); ?>
    </div>
<div class="col-md-2 col-sm-12">
    <h4><?= Yii::t('app','Activity Types');?></h4>
    <?php echo $this->renderPartial('_activity_types',array('model'=>$model)); ?>
</div>
</div>
