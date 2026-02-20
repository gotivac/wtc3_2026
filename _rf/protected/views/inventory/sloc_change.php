<h5 class="text-center">ZAMENA SLOC</h5>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'pick-form',
    'type'=> 'horizontal',
    'enableAjaxValidation'=>false,
)); ?>




<?php // echo $form->textFieldGroup($model,'sloc_source',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->textFieldGroup($model,'sloc_source',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>
<?php echo $form->textFieldGroup($model,'sloc_destination',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'skip')),'labelOptions'=>array('label'=>false))); ?>


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

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });


</script>