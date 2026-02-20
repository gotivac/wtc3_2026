<?php


$picked = Pick::model()->find(array('condition' => 'id = ' . $data->id . ' AND product_id IS NOT NULL'));
if ($picked !== null) {
    $alert = 'alert-warning';
} else {
    $alert = '';
}

?>



<div class="bubble <?= $alert; ?>">

    <div class="row">
        <div class="col-xs-9">
            <h4>SLOC: <?= $data->activityPalett && $data->activityPalett->inSloc ? $data->activityPalett->inSloc->sloc_code : 'N/A'; ?></h4>
            <b><?= $data->sscc_source; ?></b><br><b><?= $data->sscc_destination;?></b><br>
        </div>
        <div class="col-xs-3 text-right" style="vertical-align: middle;height: 100%">


            <?php if ($alert == 'alert-warning'):?>
                <?= CHtml::link('<i class="glyphicon glyphicon-remove"></i>',Yii::app()->createUrl('/pick/resetSplit',array('id'=>$picked->id)),array("class"=>"btn btn-danger btn-xs",'onclick' => 'if(!confirm("Da li ste sigurni da želite da poništite pikovanje?")) return false;'));?>
                <?= CHtml::link('<i class="glyphicon glyphicon-th-list"></i>',Yii::app()->createUrl('/split/locate',array('id'=>$picked->id)),array("class"=>"btn btn-success btn-xs"));?>
            <?php else : ?>
                <?= CHtml::link('<i class="glyphicon glyphicon-off"></i>',Yii::app()->createUrl('/pick/deleteSplit',array('id'=>$data->id)),array("class"=>"btn btn-danger btn-xs",'onclick' => 'if(!confirm("Da li ste sigurni da želite da odustanete od raspodele?")) return false;'));?>
                <?= CHtml::link('<i class="glyphicon glyphicon-th-large"></i>',Yii::app()->createUrl('/pick/update/'.$data->id,),array("class"=>"btn btn-warning btn-xs"));?>

            <?php endif; ?>

        </div>

        <p class="col-xs-12 text-right">
            <?php if ($data->activityPalett):?>
            <?php foreach ($data->activityPalett->hasProducts as $hasProduct):?>
            <?=$hasProduct->product_barcode.' - '.$hasProduct->stockQuantity;?> kom<br>
            <?php endforeach; ?>
    <?php endif; ?>
        </p>

    </div>
</div>
