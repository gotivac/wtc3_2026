<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'client-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>





<?php echo $form->textFieldGroup($model, 'title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textAreaGroup($model, 'official_title', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('rows' => 6)))); ?>

<?php echo $form->textFieldGroup($model, 'tax_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'domain', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker', 'onchange'=>'filterSections(this.value)')))); ?>
<div class="form-group">
    <label class=" col-sm-3 control-label"><?= Yii::t('app', 'Sections'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?= CHtml::dropDownList('ClientHasSection[section_id]', $section_ids, CHtml::listData(Section::model()->findAll(array('condition' => $model->location ? 'location_id=' . $model->location->id : '')), 'id', 'title'), array('class' => 'form-control selectpicker', 'multiple' => 'multiple')); ?>
    </div>
</div>




    <?php $sections = !empty($section_ids) ? Section::model()->findAll(array('condition'=>'id IN ('.implode(',',$section_ids).')')) : array();?>
    <?=  $form->dropDownListGroup($model, 'section_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData($sections, 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>




<?php echo $form->dropDownListGroup($model, 'unloading_level_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(UnloadingLevel::model()->findAll(array('order' => 'title')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
<div class="form-group">
    <label class=" col-sm-3 control-label"><?= Yii::t('app', 'Storage Types'); ?></label>
    <div class="col-md-6 col-sm-12 col-sm-9">
        <?= CHtml::dropDownList('ClientHasStorageType[storage_type_id]', $storage_type_ids, CHtml::listData(StorageType::model()->findAll(), 'id', 'title'), array('class' => 'form-control selectpicker', 'multiple' => 'multiple')); ?>
    </div>
</div>

<?php echo $form->checkBoxListGroup($model, 'pick_methods', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('data'=>array('FIFO'=>'FIFO','FEFO'=>'FEFO', 'BATCH'=>Yii::t('app','BATCH'), 'SNAKE'=>Yii::t('app','SNAKE')),'htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model,'client_identification',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'postal_code',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'city',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'address',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'country',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'contact_person',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'phone',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'company_number',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)))); ?>

<?php echo $form->dropDownListGroup($model, 'client_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(ClientType::model()->findAll(array('order' => 'id')), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker', 'onchange'=>'filterSections(this.value)')))); ?>

<div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script>
    function filterSections(id) {
        $.ajax({
            url: '<?= Yii::app()->createUrl("systemSettings/default/ajaxGetSections");?>',
            type: 'post',
            data: {'location_id': id},
            success: function (data) {
                $('#ClientHasSection_section_id').html(data).selectpicker('refresh');
            }
        });
    }
</script>

<script>
    $('#ClientHasSection_section_id').on('change', function () {
        let ids = $(this).val();
        if (ids != null) {
            $.ajax({
                url: '<?= Yii::app()->createUrl("systemSettings/section/ajaxDefaultDropdown");?>',
                type: 'post',
                data: {'ids': ids},
                dataType: 'html',
                success: function (data) {
                    if (data.length > 0) {
                        $('#Client_section_id').html(data).selectpicker('refresh');
                    }

                }
            });
        } else {
            $('#Client_section_id').html('').selectpicker('refresh');

        }
    });
</script>