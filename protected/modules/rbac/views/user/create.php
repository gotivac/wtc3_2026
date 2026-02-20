<?php

$this->breadcrumbs = array(
    Yii::t('app', 'Users') => array('index'),
    Yii::t('app', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Create'), 'url' => array('create'), 'active' => true),
);
?>

<div class="alert-placeholder"></div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>