<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'auth-role-activity-type-form',
    'type'=> 'vertical',
    'enableAjaxValidation'=>false,
)); ?>




<?php
foreach(ActivityType::model()->findAll() as $activityType):?>



<div class="form-group col-md-12 col-xs-12">
    <div class="col-md-10"><strong><?= $activityType->title;?></strong></div>

    <div class="col-md-2">
    <input type="checkbox" name="AuthRoleActivityType[<?=$activityType->id;?>]" id="" value="1" <?php echo AuthRoleActivityType::model()->findByAttributes(array('activity_type_id'=>$activityType->id,'auth_role_id'=>$model->id)) ? 'checked' : ''; ?>>
    </div>

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
