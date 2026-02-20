<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'add-client-form',
    'type' => 'vertical',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
));
?>
<div class="col-md-3 well">
    <h4><?= Yii::t('app', 'Add Client'); ?></h4>


    <div class="form-group" style="padding:10px">
        <?php echo $form::hiddenField($time_slot_detail, 'time_slot_id', array('value' => $model->id)); ?>
        <?php echo $form->dropDownListGroup($time_slot_detail, 'client_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData($clients, 'id', 'title'), 'htmlOptions' => array('empty' => Yii::t('app', 'Client'), 'class' => 'selectpicker')))); ?>
        <?php echo $form->numberFieldGroup($time_slot_detail, 'paletts', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array()))); ?>

        <div class="form-group">
            <label class="control-label required"
                   for="TimeSlotAttachment_filename"><?php echo Yii::t('app', 'Documents'); ?> </label>

            <div class="input-group">

                <input class="form-control" type="text" placeholder="<?php echo Yii::t('app', 'Attach File'); ?>"
                       name="TimeSlotDetailsAttachment[filename]" id="TimeSlotDetailsAttachment" readonly>

                <div class="input-group-addon" id="uploadTimeSlotDetailsAttachment" style="cursor:pointer;"><i
                            class="glyphicon glyphicon-eject"></i></div>

            </div>


            <div style="display:none">
                <input type="file" name="TimeSlotDetailsAttachment[files][]" id="TimeSlotDetailsAttachment_files" multiple>
            </div>


        </div>
    </div>
    <div class="form-group text-center">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => Yii::t('app', 'Add Client'),
        ));
        ?>

    </div>
</div>
<?php $this->endWidget(); ?>


<div class="col-md-9">

    <?php echo $this->renderPartial('_details_update', array('model' => $model, 'clients' => $clients, 'time_slot_details' => $time_slot_details)); ?>
</div>

<div class="clearfix"></div>
<div class="alert-placeholder"></div>
<?php if ($model->location != null): ?>
    <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
        <?= CHtml::link(Yii::t('app', 'Time'), Yii::app()->createurl('timeSlot', array('update' => $model->id)) . '?tab=2', array('class' => 'btn btn-success')); ?>
    </div>
<?php endif; ?>

<script>
    $("#uploadTimeSlotDetailsAttachment").on("click", function () {
        $("#TimeSlotDetailsAttachment_files").trigger("click");
    });

    $("#TimeSlotDetailsAttachment_files").on("change", function (t) {
        var filenames = '';
        for (var i = 0; i < this.files.length; i++) {
            filenames = filenames + this.files[i].name + ', ';
        }
        filenames = filenames.substring(0, filenames.length - 2);

        $("#TimeSlotDetailsAttachment").val(filenames);

    });
</script>