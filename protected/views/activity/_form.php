<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'activity-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
)); ?>

<?php

echo $form->switchGroup($model, 'urgent', array(
        'widgetOptions' => array(
            'events' => array(
                'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
            ),
            'options' => array(
                'onText' => Yii::t('app', 'Yes'),
                'offText' => Yii::t('app', 'No'),
            ),

        ),

    )
);
?>

<?php echo $form->dropDownListGroup($model, 'activity_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(ActivityType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('style' => ($model->order_request_id) ? 'pointer-events:none;background-color:#efefef;' : '', 'empty' => '')))); ?>

<?php echo $form->hiddenField($model, 'direction'); ?>

<?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->byUser(), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'style' => ($model->order_request_id) ? 'pointer-events:none;background-color:#efefef;' : '')))); ?>

<?php echo $form->dropDownListGroup($model, 'gate_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Gate::model()->byLocation($model->location_id), 'id', 'title'), 'htmlOptions' => array('empty' => '')))); ?>

<?php echo $form->dateTimePickerGroup($model, 'truck_arrived_datetime', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt"></i>')); ?>

<?php echo $form->textFieldGroup($model, 'license_plate', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textAreaGroup($model, 'shipper_data', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 2)))); ?>

<?php echo $form->textAreaGroup($model, 'driver_data', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 2)))); ?>
<?php if (!$model->isNewRecord): ?>


        <?php
        echo $form->switchGroup($model, 'truck_checked', array(
                'widgetOptions' => array(
                    'events' => array(
                        'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
                    ),
                    'options' => array(
                        'onText' => Yii::t('app', 'Yes'),
                        'offText' => Yii::t('app', 'No'),
                    ),
                ),

            )
        );
        ?>



        <?php
        echo $form->switchGroup($model, 'driver_present', array(
                'widgetOptions' => array(
                    'events' => array(
                        'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
                    ),
                    'options' => array(
                        'onText' => Yii::t('app', 'Yes'),
                        'offText' => Yii::t('app', 'No'),
                    )
                ),

            )
        );
        ?>

<!--
    <div class="col-md-4">
        <label class="control-label"><?=Yii::t('app','Documents Ok');?></label>
        <?php /*
        echo $form->switchGroup($model, 'documents_ok', array(
                'widgetOptions' => array(
                    'events' => array(
                        'switchChange' => 'js:function(event, state) {
							console.log(this); // DOM element
							console.log(event); // jQuery event
							console.log(state); // true | false
							}',
                    ),
                    'options' => array(
                        'onText' => Yii::t('app', 'Yes'),
                        'offText' => Yii::t('app', 'No'),
                    )
                ),
                'labelOptions'=>array(
                    'label' => false
                )
            )
        ); */
        ?>
    </div>
    -->
    <div id="dispatch">
        <?php echo $form->dateTimePickerGroup($model, 'truck_dispatch_datetime', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt"></i>')); ?>
    </div>

    <?php echo $form->dateTimePickerGroup($model, 'system_acceptance_datetime', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('options' => array('format' => 'd.m.yyyy h:ii', 'language' => 'rs', 'autoclose' => true), 'htmlOptions' => array()), 'append' => '<i class="glyphicon glyphicon-pushpin auto-dt"></i>')); ?>
    <div id="customs-div" <?php if ($model->direction != "in") echo 'style="display:none"'; ?>>
        <?php echo $form->dropDownListGroup($model, 'customs', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => array('Domaći dobavljač' => Yii::t('app', 'Domestic Supplier'), 'Neocarinjeno' => Yii::t('app', 'Not Cleared'), 'Ocarinjeno' => Yii::t('app', 'Cleared')), 'htmlOptions' => array()), 'append' => ($model->customs_user_id != null) ? '<span class="small">' . User::model()->findByPk($model->customs_user_id)->name . ' &bullet; ' . date('d.m.Y \u H:i:s', strtotime($model->customs_datetime)) . '</span>' : '')); ?>
    </div>
    <?php echo $form->textAreaGroup($model, 'notes', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>

    <div class="form-group">
        <label class="control-label col-sm-3 required"
               for="ActivityAttachment_filename"><?php echo Yii::t('app', 'Documents'); ?> </label>

        <div class="col-md-6 col-sm-12 col-sm-9">
        <div class="input-group ">

            <input class="form-control" type="text" placeholder="<?php echo Yii::t('app', 'Attach File'); ?>"
                   name="ActivityAttachment[filename]" id="ActivityAttachment" readonly>

            <div class="input-group-addon" id="uploadActivityAttachment" style="cursor:pointer;"><i
                        class="glyphicon glyphicon-eject"></i></div>

        </div>
        </div>


        <div style="display:none">
            <input type="file" name="ActivityAttachment[files][]" id="ActivityAttachment_files" multiple>
        </div>


    </div>

<?php endif; ?>

<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $('.auto-dt').on('click', function () {
        var dt = new Date($.now());
        var dtf = dt.getDate() + "." + (dt.getMonth() + 1) + "." + dt.getFullYear() + " " + addZero(dt.getHours()) + ":" + addZero(dt.getMinutes());
        $(this).parent().prev().val(dtf);
    });
</script>

<script>
    $("#uploadActivityAttachment").on("click", function () {
        $("#ActivityAttachment_files").trigger("click");
    });

    $("#ActivityAttachment_files").on("change", function (t) {
        var filenames = '';
        for (var i = 0; i < this.files.length; i++) {
            filenames = filenames + this.files[i].name + ', ';
        }
        filenames = filenames.substring(0, filenames.length - 2);

        $("#ActivityAttachment").val(filenames);

    });
</script>