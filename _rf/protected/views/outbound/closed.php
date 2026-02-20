<hr>

<div class="alert-placeholder"><?php
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
</div>
<hr>
<p class="text-center">Nalog <?=$activity_order->order_number; ?> zatvoren.</p>
<?php if ($close):?>
<p class="text-center">Otpremnica spremna.</p>
<?php endif; ?>
<hr>

<div class="col-xs-12 text-center">

    <a href="<?=Yii::app()->createUrl("/outbound");?>" class="btn btn-success">Nazad</a>
</div>