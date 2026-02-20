<h5 class="text-center">RELOKACIJA</h5>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'pick-form',
    'type'=> 'horizontal',
    'enableAjaxValidation'=>false,
)); ?>




<?php // echo $form->textFieldGroup($model,'sloc_source',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->textFieldGroup($model,'sscc',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->textFieldGroup($model,'sloc_destination',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->dropDownListGroup($model, 'storage_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(StorageType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array()))); ?>

<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>
<div class="clearfix"></div>
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

<script>
    $(document).ready(function () {
        $('#Pick_sloc_code').focus().select();

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });

    $('#Relocate_sscc').on('change',function(){
        let sscc = $(this).val();
        let sloc_code = $('#Relocate_sloc_source').val();
        $.ajax({
           url: '<?= Yii::app()->createUrl("relocate/ajaxGetStorageType");?>',
            type: 'post',
            data: {'sscc':sscc,'sloc_code':sloc_code},
            dataType: 'json'
            success: function(data) {
               if (data.success == 1) {
                   $('#Relocate_storage_type_id').val(data.storage_type_id);
               }
            }
        });
    })
</script>