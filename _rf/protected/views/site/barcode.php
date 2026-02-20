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
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'failure-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
        ));
?>


<div class="row">
<?php foreach ($model as $label => $value):?>
<div class="form-group">
<label class="control-label col-md-3 col-sm-12"><?php echo Yii::t('app',ucfirst($label));?></label>
<div class="col-md-6 col-sm-12">
<input class="form-control" value="<?php echo $value;?>" name="Barcode[<?php echo $label;?>]" required="required" type="number">
</div>
</div>
<?php endforeach;?>
</div>


<div class="clearfix"></div>




<div class="form-actions">
    <?php
    $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' =>  Yii::t('app', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>