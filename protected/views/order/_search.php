<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'id'=>'filter-form',
)); ?>
<div class="row">
<div class="col-md-2">
    <?php echo $form->textFieldGroup($model, 'order_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>

<div class="col-md-2">
    <?php echo $form->dropDownListGroup($model, 'activity_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(ActivityType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>
</div>
<div class="col-md-2">
    <?php echo $form->dropDownListGroup($model, 'location_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Location::model()->findAll(), 'id', 'title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>
</div>


<div class="col-md-2">
    <?php echo $form->textFieldGroup($model, 'load_list', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>

<div class="col-md-2">
    <?php echo $form->dropDownListGroup($model, 'delivery_type_search', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Yii::app()->db->createCommand('SELECT DISTINCT delivery_type id,  delivery_type title FROM order_client WHERE delivery_type IS NOT NULL AND delivery_type <> ""')->queryAll(),'id','title'), 'htmlOptions' => array('empty' => '','class'=>'selectpicker')))); ?>
</div>

    <div class="col-md-1 text-right">
        <div class="form-group">
            <label class="control-label">&nbsp;</label><br>
            <?php $this->widget('booster.widgets.TbButton', array(
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => 'Filter',
                'htmlOptions' => array('name' => 'filter','class'=>'col-md-12'),
            )); ?>
        </div>
    </div>
    <div class="col-md-1 text-right">
        <div class="form-group">
            <label class="control-label">&nbsp;</label><br>
            <a href="" onclick="resetFilterForm();return false;"class="btn btn-warning col-md-12">Reset</a>

        </div>
    </div>

</div>
<div class="row">
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?=Yii::t('app','Created');?></label>
    <?php $this->widget('booster.widgets.TbDatePicker', array(
    'name' => 'OrderRequest[created_dt]',
    'model' => $model,
    'attribute' => 'created_dt',
    'options' => array(
    'format' => 'dd.mm.yyyy',
    'language' => 'rs-latin',
    'autoclose' => 'true'
    ),
    'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <label class="control-label"><?=Yii::t('app','Finished From');?></label>
        <?php $this->widget('booster.widgets.TbDateTimePicker', array(
            'name' => 'OrderRequest[finished_from]',
            'model' => $model,
            'attribute' => 'finished_from',
            'options' => array(
                'format' => 'dd.mm.yyyy hh:ii',
                'language' => 'rs-latin',
                'autoclose' => 'true'
            ),
            'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
    </div>
</div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app','Finished To');?></label>
            <?php $this->widget('booster.widgets.TbDateTimePicker', array(
                'name' => 'OrderRequest[finished_to]',
                'model' => $model,
                'attribute' => 'finished_to',
                'options' => array(
                    'format' => 'dd.mm.yyyy hh:ii',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app','Delivered From');?></label>
            <?php $this->widget('booster.widgets.TbDateTimePicker', array(
                'name' => 'OrderRequest[delivered_from]',
                'model' => $model,
                'attribute' => 'delivered_from',
                'options' => array(
                    'format' => 'dd.mm.yyyy hh:ii',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app','Delivered To');?></label>
            <?php $this->widget('booster.widgets.TbDateTimePicker', array(
                'name' => 'OrderRequest[delivered_to]',
                'model' => $model,
                'attribute' => 'delivered_to',
                'options' => array(
                    'format' => 'dd.mm.yyyy hh:ii',
                    'language' => 'rs-latin',
                    'autoclose' => 'true'
                ),
                'htmlOptions' => array('placeholder' => '', 'class' => 'col-md-1 col-lg-1 form-control')));?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <div class="col-md-1 text-right">
        <div class="form-group">
            <label class="control-label">&nbsp;</label><br>
            <?php $this->widget('booster.widgets.TbButton', array(
                'buttonType' => 'default',
                'context' => 'success',
                'label' => 'Excel',
                'id' => 'excel',
                 'htmlOptions' => array('name' => 'excel','class'=>'col-md-12'),

            )); ?>
        </div>
    </div>
</div>


<div class="clearfix"></div>
<hr>

<script>
    $('#excel').on('click',function(){
        let data = $('#filter-form').serialize();
        location.href=location.href+'?'+data+'&excel=true';
    })

    function resetFilterForm() {

        $('#OrderRequest_order_number').val('');
        $('#OrderRequest_activity_type_id').val('');
        $('#OrderRequest_location_id').val('');
        $('#OrderRequest_load_list').val('');
        $('#OrderRequest_delivery_type_search').val('');
        $('#OrderRequest_created_dt').val('');
        $('#OrderRequest_finished_from').val('');
        $('#OrderRequest_finished_to').val('');
        $('#OrderRequest_delivered_from').val('');
        $('#OrderRequest_delivered_to').val('');


        $('.selectpicker').selectpicker('refresh');
        $('#filter-form').submit();
    }
</script>
