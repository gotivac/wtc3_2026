<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'location-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>


<div class="row">
    <div class="col-md-6 col-sm-12">
        <?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

        <?php echo $form->textAreaGroup($model, 'address', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 4)))); ?>

        <?php echo $form->textFieldGroup($model, 'email', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

        <?php echo $form->textAreaGroup($model, 'description', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 4)))); ?>

        <?php echo $form->numberFieldGroup($model, 'inbound_palletes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255, 'min' => 1)))); ?>
        <?php echo $form->numberFieldGroup($model, 'inbound_trucks', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255, 'min' => 1)))); ?>
        <?php echo $form->numberFieldGroup($model, 'outbound_palletes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255, 'min' => 1)))); ?>
        <?php echo $form->numberFieldGroup($model, 'outbound_trucks', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255, 'min' => 1)))); ?>
        <?php echo $form->numberFieldGroup($model, 'system_acceptance', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255, 'min' => 1)))); ?>
    </div>
    <div class="col-md-6 col-sm-12">
        <h4>Time Slot Manager</h4>
        <hr>
        <h5><?=Yii::t('app','Scheduling');?></h5>
        <?php echo $form->numberFieldGroup($model, 'min_days', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)),'append'=>Yii::t('app','days'))); ?>
        <?php echo $form->numberFieldGroup($model, 'max_days', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)),'append'=>Yii::t('app','days'))); ?>
        <hr>
            <h5><?= Yii::t('app', 'Disabled Days'); ?></h5>

            <?php foreach (Helpers::getWeekdays() as $key => $weekday): ?>


                    <input type="checkbox" name="Location[disabled_days][<?= $key; ?>]" <?= is_array($model->disabled_days) && in_array($key, $model->disabled_days) ? 'checked' : ''; ?>>&nbsp;
                    <label class="control-label" style="vertical-align: top"> <?= $weekday; ?> </label>


            <?php endforeach; ?>

    <div class="clearfix"></div>
        <hr>
        <h5><?=Yii::t('app','Disabled Dates');?></h5>

            <div class="form-group col-md-4">
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
        <div class="col-md-6">
            <a href="javascript:void(0);" onclick="disableDate()"
               class="btn btn-success"><?= Yii::t('app', 'Disable'); ?></a>
        </div>
        <div class="clearfix"></div>
        <div id="disabled-dates">
            <?php if (is_array($model->disabled_dates)): ?>
            <?php foreach ($model->disabled_dates as $disabled_date): ?>

                <div class="form-group col-md-3">
                    <div class="input-group margin-right-10">
                        <input type="text" value="<?= date('d.m.Y', strtotime($disabled_date)); ?>" readonly
                               name="Location[disabled_dates][]" class="form-control">
                        <span class="input-group-addon" style="cursor:pointer" onclick="removeDate(this);"><i
                                    class="glyphicon glyphicon-remove"></i></span>
                    </div>
                </div>

            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    function removeDate(rm) {
        $(rm).parent().parent().remove();
    }

    function disableDate() {
        let disabledDate = $('#disableDate').val();
        let data = '<div class="form-group col-md-3">' +
            '<div class="input-group margin-right-10">' +
            '<input type="text" value="' + disabledDate + '" readonly name="Location[disabled_dates][]" class="form-control">' +
            '<span class="input-group-addon" style="cursor:pointer" onclick="removeDate(this);"><i class="glyphicon glyphicon-remove"></i></span>' +
            '</div>' +
            '</div>';
        $('#disabled-dates').append(data);
        $('#disableDate').val('');


    }
</script>
