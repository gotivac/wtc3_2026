<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Time Slot Settings')
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
            'error' => array('closeText' => Yii::t('app', 'Error')),
        ),
    ));
    ?>

</div>
<div class="col-md-2 col-sm-12">
    <h4><?= Yii::t('app', 'Disable date'); ?></h4>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <?php $this->widget('booster.widgets.TbDatePicker', array(
                'name' => 'disableDate',
                'htmlOptions' => array(
                    'class' => 'form-control'
                )
            )); ?>
        </div>
    </div>
    <div class="form-group text-right">
        <a href="javascript:void(0);" onclick="disableDate()"
           class="btn btn-success"><?= Yii::t('app', 'Disable'); ?></a>
    </div>


</div>

<div class="col-md-10">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'client-form',
        'type' => 'vertical',
        'enableAjaxValidation' => false,
    )); ?>

    <div class="col-md-3 col-sm-12" id="disabled-dates">
        <h4><?= Yii::t('app', 'Disabled dates'); ?></h4>

        <?php foreach ($model->disabledDates as $disabledDate): ?>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" value="<?= date('d.m.Y', strtotime($disabledDate)); ?>" readonly
                               name="TimeSlotSettings[disabledDates][]" class="form-control">
                        <span class="input-group-addon" style="cursor:pointer" onclick="removeDate(this);"><i
                                    class="glyphicon glyphicon-remove"></i></span>
                    </div>
                </div>

        <?php endforeach; ?>

    </div>
    <div class="col-md-3 col-sm-12" style="padding-left:100px">
        <h4><?= Yii::t('app', 'Disabled days'); ?></h4>
        <?php foreach (Helpers::getWeekdays() as $key => $weekday): ?>
            <div class="form-group">
                <input type="checkbox"
                       name="TimeSlotSettings[disabledDays][<?=$key;?>]" <?= in_array($key, $model->disabledDays) ? 'checked' : ''; ?>>
                <label class="control-label" style="vertical-align: middle"> <?= $weekday; ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-md-3 col-sm-12">
        <h4><?= Yii::t('app', 'Earliest') . ' - ' . Yii::t('app', 'Latest'); ?></h4>
        <div class="form-group">
            <label class="control-label"><?= Yii::t('app', 'Earliest'); ?></label>
            <div class="input-group">
                <input type="number" name="TimeSlotSettings[minDate]" class="form-control" value="<?= $model->minDate; ?>">
                <span class="input-group-addon"><?= Yii::t('app', 'days'); ?></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label"><?= Yii::t('app', 'Latest'); ?></label>
            <div class="input-group">
                <input type="number" name="TimeSlotSettings[maxDate]" class="form-control" value="<?= $model->maxDate; ?>">
                <span class="input-group-addon"><?= Yii::t('app', 'days'); ?></span>
            </div>
        </div>

    </div>
    <div class="col-md-3 col-sm-12">
        <h4><?= Yii::t('app', 'Working Time')?></h4>
        <div class="form-group">
            <label class="control-label"><?= Yii::t('app', 'From'); ?></label>

                <?php $this->widget('booster.widgets.TbTimePicker',array('name'=>'TimeSlotSettings[tsm_start_time]','value'=>$model->tsm_start_time, 'options'=>array('showMeridian'=>false),'htmlOptions'=>array('class'=>'form-control')));?>

        </div>
        <div class="form-group">
            <label class="control-label"><?= Yii::t('app', 'To'); ?></label>

                <?php $this->widget('booster.widgets.TbTimePicker',array('name'=>'TimeSlotSettings[tsm_end_time]','value'=>$model->tsm_end_time, 'options'=>array('showMeridian'=>false),'htmlOptions'=>array('class'=>'form-control')));?>



        </div>

    </div>

    <div class="clearfix"></div>
    <div class="form-actions">
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType'=>'submit',
            'context'=>'primary',
            'label' => Yii::t('app', 'Save'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>



<script>
    function removeDate(rm) {
        $(rm).parent().parent().remove();
    }

    function disableDate() {
        let disabledDate = $('#disableDate').val();
        let data = '<div class="form-group">' +
            '<div class="input-group">' +
            '<input type="text" value="' + disabledDate + '" readonly name="TimeSlotSettings[disabledDates][]" class="form-control">' +
            '<span class="input-group-addon" style="cursor:pointer" onclick="removeDate(this);"><i class="glyphicon glyphicon-remove"></i></span>' +
            '</div>' +
            '</div>';
        $('#disabled-dates').append(data);
        $('#disableDate').val('');


    }
</script>