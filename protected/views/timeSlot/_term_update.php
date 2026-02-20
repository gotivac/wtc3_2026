
<?php if ($model->location):?>
    <div class="col-md-9 col-md-offset-3 col-sm-12 text-info"><?php echo $model->location->title.' * '.$model->section->title; ?></div>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'time-slot-term-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>

<?php if (strpos(Yii::app()->user->roles, 'administrator') === false): ?>
    <?php echo $form->datePickerGroup($model, 'defined_date', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('minDate' => date('d.m.Y', strtotime(' +' . $model->location->min_days . ' Days')), 'maxDate' => date('d.m.Y', strtotime(' +' . $model->location->max_days . ' Days')), 'beforeShowDay' => 'js:checkDate'), 'htmlOptions' => array()), 'prepend' => '<i class="glyphicon glyphicon-calendar"></i>')); ?>
<?php else: ?>
    <?php echo $form->datePickerGroup($model, 'defined_date', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('minDate' => date('d.m.Y'), 'beforeShowDay' => 'js:checkDate'), 'htmlOptions' => array()), 'prepend' => '<i class="glyphicon glyphicon-calendar"></i>')); ?>
<?php endif; ?>

<?php echo $form->dropDownListGroup($model, 'start_time', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('data' => $terms, 'htmlOptions' => array('empty' => '')), 'append' => Yii::t('app', 'End Time') . ': '.$model->end_time)); ?>

<input type="hidden" id="end_time" name="TimeSlot[end_time]"
       value="<?php echo $model->end_time ? $model->end_time : '0'; ?>">
<?php echo $form->textAreaGroup($model, 'notes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>


<div class="form-actions">
    <?php
    $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
    'htmlOptions' => array('name'=>'set-time'),

        'label' => !$model->gate ? Yii::t('app', 'Create Time Slot') : Yii::t('app','Update Time Slot'),

    ));
    ?>
    <?php
    $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'reset',
        'context' => 'danger',
        'label' => !$model->gate ? Yii::t('app', 'Reset') : Yii::t('app','Delete'),
        'htmlOptions' => array(
            'onclick' => 'resetAll()'
        )
    ));
    ?>
</div>

<?php $this->endWidget();?>

<script>

    $('#TimeSlot_defined_date').on('change',function (){

            $('#TimeSlot_start_time').val('');
            $('#time-slot-term-form').submit();

    });

    $('#TimeSlot_start_time').on('change',function(){

        let start_time = $(this).val();

        $.ajax({
            url: '<?=Yii::app()->createUrl("timeSlot/ajaxGetTermEnd/".$model->id);?>',
            data: {'start_time':start_time},
            type: 'post',
            success:function(data){
                $('#TimeSlot_start_time').next('.input-group-addon').html('<?php echo Yii::t('app','End Time');?>'+': ' + data);
                $('#end_time').val(data);
            }
        });
    });

    function resetAll()
    {
        if (!confirm('<?=Yii::t("app","Are you sure?");?>')) {
            return;
        }
        $.ajax({
            url: '<?=Yii::app()->createUrl("timeSlot/ajaxReset/".$model->id);?>',

            type: 'post',
            success:function(data){
                location.href = '<?=Yii::app()->createUrl("timeSlot/create");?>';
            }
        });
    }
</script>


    <script>
        function checkDate(definedDate) {

            let weekDay = definedDate.getDay();

            let disabledDays = [<?= is_array($model->location->disabled_days) ? implode(",", $model->location->disabled_days) : '';?>];
            if (disabledDays.includes(weekDay)) {
                return [0, ''];
            } else {
                let disabledDates = [<?= is_array($model->location->disabled_dates) ? "'" . implode("','", $model->location->disabled_dates) . "'" : '';?>];
                let date = definedDate.getFullYear() + "-";
                let month = "0" + (1 + definedDate.getMonth());
                month = month.substr(-2);
                let day = "0" + definedDate.getDate();
                day = day.substr(-2);
                date += month + "-" + day;

                if (disabledDates.includes(date)) {
                    return [0, ''];
                } else {
                    return [1, ''];
                }
            }
        }

    </script>

<?php endif; ?>