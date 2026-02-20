<?php
$alert = '';
if ($data->totalProducts == $data->totalPickedProducts) {
    $alert = 'alert-warning';
}

if (count($data->loadedSSCCs) > 0 && count($data->loadedSSCCs) == count($data->pickedSSCCs)) {
    $alert = 'alert-success';
}

?>


<div class="bubble <?= $alert; ?>">

    <div class="row">
        <div class="col-xs-9">
            <h4><?= $data->activity->gate->title; ?></h4>

<p>
            Klijent: <?= $data->client->title; ?><br>
            <?= $data->customerSupplier->title;?><br>
            Način isporuke: <?=$data->delivery_type;?><br>
            Broj naloga: <?= $data->order_number; ?>
</p>
        </div>
        <div class="col-xs-3 text-right" style="vertical-align: middle;height: 100%">
            <?php  if ($data->activity->system_acceptance == 0): ?>

                <a class="btn btn-info btn-xs" href="<?= Yii::app()->createUrl('/outbound/order/' . $data->id); ?>"
                   title="Pikovanje"><i class="glyphicon glyphicon-th-large"></i></a>
            <?php  endif; ?>
            <?php if (count($data->activityPaletts) > count($data->loadedSSCCs)): ?>
                <a class="btn btn-primary btn-xs" href="<?= Yii::app()->createUrl('/outbound/load/' . $data->id); ?>"
                   title="Utovar"><i class="glyphicon glyphicon-th-list"></i></a>
            <?php endif; ?>

            <?php if ($data->totalProducts != $data->totalPickedProducts) : ?>
            <a class="btn btn-danger btn-xs" href="<?= Yii::app()->createUrl('/outbound/reset/' . $data->id); ?>"
               title="Reset" onclick="return confirm('Da li ste sigurni da želite da resetujete nalog?');"><i class="glyphicon glyphicon-off"></i></a>
            <?php endif; ?>
        </div>
    </div>
</div>
