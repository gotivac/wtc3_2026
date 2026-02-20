<h5>
    <div class="text-left col-xs-10">
        <?= $order->order_number;?> &bull; <?=$order->client->title;?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/outbound/order/'.$order->id);?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>

<div class="clearfix"></div>
<h4 class="text-center">Izaberite metod sakupljanja</h4>
<hr>
<p>
<div class="col-xs-12 text-center">
    <a  href="<?=Yii::app()->createUrl('/outbound/pickPalett/' . $order->id);?>" class="btn btn-small btn-info"><i class="glyphicon glyphicon-th-list"></i> Palete</a>
</div>
</p>
<p>&nbsp;</p>
<p>
<div class="col-xs-12 text-center">
    <a href="<?=Yii::app()->createUrl('/outbound/pickProduct/' . $order->id);?>" class="btn btn-small btn-info"><i class="glyphicon glyphicon-th-large"></i> Proizvodi</a>
</div>
</p>
<p>&nbsp;</p>
<p>
<div class="col-xs-12 text-center">
    <a href="<?=Yii::app()->createUrl('/outbound/closeOrder/' . $order->id);?>" class="btn btn-small btn-danger" onclick="return confirm('Da li ste sigurni da zatvarate nalog?');"><i class="glyphicon glyphicon-off"></i> Zatvori</a>
</div>
</p>
<div class="clearfix">

</div>
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