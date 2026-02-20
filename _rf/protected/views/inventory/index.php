<h5>
    <div class="text-center col-xs-12">
        POPIS
    </div>

</h5>
<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'sloc-form',
    'type'=> 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div class="col-xs-9">
    <?php echo $form->textFieldGroup($model, 'sloc_code', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>
</div>
<div class="col-xs-3 text-left">


    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

</div>

<?php $this->endWidget(); ?>
<div class="clearfix"></div>
<hr>
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
