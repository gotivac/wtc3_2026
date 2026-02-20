<h5 class="text-center"> <?= $activity->gate->title . '  &bull;  ' . CHtml::link('Preostalo paleta: '. count($activity->unlocated),Yii::app()->createUrl("/inbound/viewActivity/".$activity->id));?></h5>


<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'sloc_has_activity_palett',
    'type' => 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>


<?php echo $form->textFieldGroup($model, 'sloc_code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255,'class'=>'skip')), 'labelOptions' => array('label' => false))); ?>
<?php echo $form->textFieldGroup($model, 'sscc', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)), 'labelOptions' => array('label' => false))); ?>
<?php echo $form->dropDownListGroup($model, 'storage_type_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(StorageType::model()->findAll(), 'id', 'title'), 'htmlOptions' => array()))); ?>

<div class="form-actions">

    <button type="submit" class="btn btn-primary">OK</button>

</div>
<?php $this->endWidget(); ?>
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
    $(document).ready(function(){
        $('#SlocHasActivityPalett_sloc_code').focus();
    });

    $(".skip").keypress(function (event) {
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
            var thisIndex = $(this).index('input:text');
            var next = thisIndex + 1;
            $('input:text').eq(next).focus();


        }
    });
</script>