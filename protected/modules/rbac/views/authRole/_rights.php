<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'auth-role-form',
    'type'=> 'vertical',
    'enableAjaxValidation'=>false,
)); ?>




<?php
foreach($model->rights as $controller => $actions):?>

<?php $ctrl = AuthController::model()->findByAttributes(array('title' => $controller));?>

<div class="form-group col-md-12 col-xs-12">
    <div class="col-md-2"><strong><?= $ctrl->name;?></strong></div>
<?php foreach ($actions as $action => $value):?>
    <div class="col-md-2">
    <input type="checkbox" name="AuthRoleCan[<?=$controller;?>][<?=$action;?>]" id="" value="1" <?php echo $value != null ? 'checked' : ''; ?>>
    <label class="" for=""><?=strtoupper($action);?></label>
    </div>
<?php endforeach;?>
</div>
<?php endforeach;?>

<div class="clearfix"></div>
<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType'=>'submit',
        'context'=>'primary',
        'label' => Yii::t('app', 'Save'),
    )); ?>
</div>


<?php $this->endWidget(); ?>
