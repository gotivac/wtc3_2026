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
            'error' => array('closeText' => false),
        ),
    ));
    ?>
</div>
<hr>
<p class="text-center"><?=$model->gate->title;?></p>
<p class="text-center"><?=$model->license_plate;?></p>
<p class="text-center">Primljeno paleta: <?= count($model->activityPaletts);?></p>
<hr>

<div class="col-xs-12 text-center">
<?php if ($model->system_acceptance == 1):?>
    <a href="<?=Yii::app()->createUrl("/inbound");?>" class="btn btn-success">Nazad</a>
    <?php else: ?>
    <a href="<?=$_SERVER['HTTP_REFERER'];?>" class="btn btn-success">Nazad</a>
    <?php endif;?>
</div>