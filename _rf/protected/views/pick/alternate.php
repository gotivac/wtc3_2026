<h5>
    <div class="text-left col-xs-10">
        <?= $model->activityOrder->order_number; ?> &bull; <?= $model->activityOrder->client->title; ?>
        &bull; <?= $model->sloc_code; ?><br>
    </div>

            <a class="btn btn-primary btn-xs"
               href="<?= Yii::app()->createUrl('/pick/update/' . $model->id); ?>"><i
                    class="glyphicon glyphicon-arrow-left"></i></a>

    </div>
</h5>
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
<p></p>

    <p class="well">
        <?= Product::model()->findByPk($model->product_id)->title; ?>
    </p>
<?php if ($model->pick_type == 'palett'): ?>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'type-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
        'htmlOptions' => array(
                'onsubmit' => 'return confirm("Da li zaista uzimate samo proizvode?");',
        )
)); ?>
<input type="hidden" name="SetProduct" value="1" />
<div class="text-center">
<button type="submit" class="btn btn-warning btn-small">Pokupi samo proizvode</button>
</div>
<hr>
<?php $this->endWidget(); ?>
<?php endif; ?>
    <?php foreach ($active_paletts as $active_palett):?>

    <div class="bubble">
        <div class="row">
            <div class="col-xs-9">
                <h4>SLOC: <?= $active_palett->activityPalett->inSloc->sloc_code; ?></h4>
                <h5>SSCC: <?= $active_palett->activityPalett->sscc; ?></h5>

            </div>
            <div class="col-xs-3 text-right" style="vertical-align: middle;height: 100%">


<form method="post" style="margin-top: 0px !important;">
    <button type="submit" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-refresh"></i></button>

    <input type="hidden" name="Alternate[<?=$model->id;?>]" value="<?=$active_palett->id;?>">
</form>



            </div>

            <h4 class="col-xs-12 text-right">Komada: <b><?= $active_palett->content['quantity']; ?></b>

            </h4>
        </div>
    </div>

    <?php endforeach;?>
