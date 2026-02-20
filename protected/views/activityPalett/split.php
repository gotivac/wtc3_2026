<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Occupied SLOCs') => array('index'),
    Yii::t('app', 'Split'),
);



$this->menu=array(
    array('label'=>Yii::t('app','Back'),'url'=>array('/slocHasActivityPalett')),
    array('label' => '<i class="glyphicon glyphicon-barcode"></i> ' . Yii::t('app', 'Sticker'), 'url' => array('activity/resSticker/'.$model->id),'encodeLabel'=>false),

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


<h2 class="text-center">
    <?php echo $model->sscc;?><br>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-barcode"></i> ' . Yii::t('app', 'Sticker'),Yii::app()->createUrl('/activity/resSticker/'.$model->id),array('class'=>'btn btn-success'));?>
</h2>

