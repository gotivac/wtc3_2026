
<?php

if (count($data->unlocated) == 0) {
    $alert = 'alert-success';
} else if (count($data->activityPaletts) == count ($data->scannedSSCCs)) {
    $alert = 'alert-warning';
} else {
    $alert = '';
}

?>


    <div class="bubble <?=$alert;?>">
        <div class="row">
        <div class="col-xs-9">
        <h4><?=$data->gate->title;?></h4>
        Kamion stigao: <?=$data->truck_arrived_datetime;?><br>
            <b>Nalozi:</b> <?=is_array($data->orderNumber) ? implode(', ',$data->orderNumber) : $data->orderNumber;?>
        </div>
        <div class="col-xs-3 text-right" style="vertical-align: middle;height: 100%">
            <?php if ($data->truck_dispatch_datetime == NULL): ?>
            <a class="btn btn-warning btn-xs" href="<?=Yii::app()->createUrl('/activityPalettHasProduct/create/'.$data->id);?>" title="Prijem"><i class="glyphicon glyphicon-th-large"></i></a>
            <?php endif; ?>
            <a class="btn btn-success btn-xs" href="<?=Yii::app()->createUrl('/inbound/locate/'.$data->id);?>" title="Lociranje"><i class="glyphicon glyphicon-th-list"></i></a>
        </div>
        </div>
    </div>
