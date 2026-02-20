<h5 class="text-center">
    <?= $order->order_number . '  &bull;  ' . $order->client->title . ' &bull; ' . CHtml::link('Utovareno paleta: ' . count($order->loadedSSCCs) . '/' . count($order->pickedSSCCs), Yii::app()->createUrl("/outbound/gateout/" . $order->id)); ?></h5>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'load-form',
    'type' => 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

<div class="col-xs-9">
    <?php echo $form->textFieldGroup($model, 'sscc_source', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)), 'labelOptions' => array('label' => false))); ?>
    <p class="help-block">Skenirajte barkod palete</p>
</div>

<div class="col-xs-3 text-left">


    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

</div>
<?php $this->endWidget(); ?>
<div class="clearfix">

</div>
<div class="col-xs-12 text-center">

    <a href="<?= Yii::app()->createUrl("/outbound/loadCut/".$order->id); ?>" class="btn btn-danger" onclick="return confirm('Da li ste sigurni da završavate kamion?');">Završi kamion</a>
</div>
<div class="clearfix"></div>
<p></p>
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
        'error' => array('closeText' => Yii::t('app', 'Error')),
    ),
));
?>