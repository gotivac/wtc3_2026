<?php
$this->breadcrumbs=array(
	Yii::t('app','Email Schedules')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
	array('label'=>Yii::t('app','List'),'url'=>array('index')),
	array('label'=>Yii::t('app','Send Now'),'url'=>'','linkOptions'=>array('onclick'=>'sendNow()')),
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

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>

<script>
    function sendNow(id)
    {
        $.ajax({
            url: '<?=Yii::app()->createUrl("/systemSettings/emailSchedule/ajaxSend/");?>',
            type: 'post',
            data: {'id':'<?=$model->id;?>'},
            success: function(){}

        });
        alert('Email će biti poslat kroz nekoliko minuta kada izveštaj bude spreman.');
    }
</script>
