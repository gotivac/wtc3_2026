<?php
$this->breadcrumbs=array(
	Yii::t('app','Time Slots')=>array('index'),
	$model->defined_date . ': ' . $model->start_time .' - '.$model->end_time =>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

	$this->menu=array(
	array('label'=>Yii::t('app','Back'),'url'=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
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
                'content' => $this->renderPartial('_form_update', array('model' => $model), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0) || $model->hasErrors()) ? true : false,
                // 'url' => '?tab=0',

            ),

            array(
                'label' => Yii::t('app', 'Clients'),
                'content' => $this->renderPartial('_detail_update',array('model'=>$model,'clients'=>$clients,'time_slot_detail' => $time_slot_detail,'time_slot_details'=>$time_slot_details),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1 || $time_slot_detail->hasErrors()) ? true : false,


            ),

            array(
                'label' => Yii::t('app', 'Time'),
                'content' => $this->renderPartial('_term_update',array('model'=>$model,'terms'=>$terms),true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 2 && !$time_slot_detail->hasErrors() && !$model->hasErrors()) ? true : false,
                'visible' => $model->location != null,


            ),





        ),
    )
);

?>