<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>


    <div class="col-sm-12 col-md-6 col-md-offset-3">
        <?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'title' => '<div class="text-right"><a href="'.Yii::app()->controller->createUrl('logout').'">LOGOUT</a></div>',
));
?>





        <div class="form">
            <?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>

<?php

$this->widget('booster.widgets.TbAlert', array(
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array(),
    'userComponentId' => 'user',
    'alerts' => array(// configurations per alert type
        // success, info, warning, error or danger
        'success' => array('closeText' => '&times;'),
        'info', // you don't need to specify full config
        'warning' => array('closeText' => false),
        'error' => array('closeText' => Yii::t('app', 'Error')),
    ),
));
?>


            <div class="form-group col-md-12 text-center">
                <?php echo CHtml::label(Yii::t('app','Code'), '', array('class' => 'control-label')); ?>
                <div class="controls col-md-offset-5">
                    <?php echo CHtml::textField('code', '', array('class' => 'col-md-4 text-center', 'maxlength' => '255')); ?>
                    


                </div>
            </div>
            <div class="form-group col-md-12 text-center">
                <?php echo Yii::t('app','We sent code to your email address.');?><br>
                <?php echo CHtml::link(Yii::t('app','Resend code'),Yii::app()->controller->createUrl('authenticate'));?>
            </div>


            <div class="buttons col-md-4">
                <?php echo CHtml::submitButton('Login', array('class' => 'btn btn btn-primary')); ?>

            </div>
            
            <div class="clearfix"></div>
            
            <?php $this->endWidget();?>


            <?php $this->endWidget();?>
        </div><!-- form -->

    </div>
    <div class="col-md-6">

    </div>

</div>
