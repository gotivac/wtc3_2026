<?php

$picked = PickWeb::model()->find(array('condition' => 'id = ' . $data->id . ' AND quantity IS NOT NULL'));
if ($picked !== null) {
    $alert = 'alert-warning';
} else {
    $alert = '';
}

?>


<div class="bubble <?= $alert; ?>">

    <div class="row">
        <div class="col-xs-9">
            <h4>SLOC: <?= $data->sloc_code; ?></h4>
            <?php if ($data->sscc_source != NULL):?>
            <h4 class="text-danger"><?=$data->sscc_source;?></h4>
            <?php endif; ?>
            <p><?= $data->product->title; ?></p>

        </div>
        <div class="col-xs-3 text-right" style="vertical-align: middle;height: 100%">


            <?php if ($alert == 'alert-warning'):?>
                <?= CHtml::link('<i class="glyphicon glyphicon-remove"></i>',Yii::app()->createUrl('/web/reset',array('id'=>$picked->id)),array("class"=>"btn btn-danger btn-xs",'onclick' => 'if(!confirm("Da li ste sigurni da želite da poništite pikovanje?")) return false;'));?>

            <?php else : ?>

                <?= CHtml::link('<i class="glyphicon glyphicon-th-large"></i>',Yii::app()->createUrl('/web/update/'.$data->id,),array("class"=>"btn btn-warning btn-xs"));?>

            <?php endif; ?>

        </div>

        <h4 class="col-xs-12 text-right">Komada: <b><?= $data->target; ?></b>
            <?php if ($data->quantity > 0): ?>
                <br>Pikovano: <b><?= $data->quantity; ?></b>
            <?php endif; ?>
        </h4>

    </div>
</div>
