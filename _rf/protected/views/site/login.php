<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>
<div class="row" id="login-form" <?php if ($visible == 'reg' || $visible == 'registered') {
    echo 'style="display:none"';
}
?>>

    <div class="col-sm-12 col-md-6 col-md-offset-3">
        <?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'title' => '<div class="text-right">Login</div>',
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




            <div class="form-group col-md-12 ">
                <?php echo $form->labelEx($model, 'email', array('class' => 'control-label')); ?>
                <div class="controls">
                    <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'maxlength' => '255')); ?>
                    <?php echo $form->error($model, 'email', array('class' => 'alert alert-danger col-md-12')); ?>


                </div>
            </div>


            <div class="form-group  col-md-12">
                <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
                <div class="controls">
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control ')); ?>
                    <?php echo $form->error($model, 'password', array('class' => 'alert alert-danger col-md-12')); ?>


                </div>
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
