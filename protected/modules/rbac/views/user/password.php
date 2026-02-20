<?php
/* @var $this UserController */
/* @var $model User */
/*
if (Yii::app()->user->roles == 'supervizor') {
    $this->breadcrumbs = array(
        Yii::t('app', 'Users') => array('index'),
        $model->name => array('update', 'id' => $model->id),
        Yii::t('app', 'Change password'),
    );
} else {
*/    
    $this->breadcrumbs = array(
        Yii::t('app', 'Change password'),
    );
/*
}
*/

$this->menu = array(
    (Yii::app()->user->roles == "supervizor") ?
    array('label' => $model->name, 'url' => array('update', 'id' => $model->id))
    :
    array('label' => Yii::t('app', 'Dashboard'), 'url' => array("\/")),

);
?>
<div class="alert-placeholder">
<?php $this->widget('booster.widgets.TbAlert', array(
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
        'error' => array('closeText' => Yii::t('app', 'Error')),
    ),
));?>
</div>
<?php $this->renderPartial('_password', array());?>