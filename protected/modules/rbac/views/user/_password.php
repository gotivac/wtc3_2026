
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
        ));
?>

        <div class="form-group">
            <label class="col-sm-3 control-label required"><?php echo Yii::t('app','New Password');?> <span class="required">*</span></label>
            <div class="col-md-6 col-sm-12 col-sm-9">
                <input type="password" id="new" name="Password[password]" class="form-control" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label required"><?php echo Yii::t('app','Confirm Password');?> <span class="required">*</span></label>
            <div class="col-md-6 col-sm-12 col-sm-9">
                <input type="password" id="confirm" name="Password[confirm]" class="form-control" required/>
            </div>
        </div>



      

        <div class="form-actions">
            <?php
            $this->widget('booster.widgets.TbButton', array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => Yii::t('app', 'Update'),
            ));
            ?>
        </div>
</div>
<?php $this->endWidget(); ?>

