<h5>
<div class="col-xs-10 text-left">
    <b><?= $web_order->order_number; ?></b>
</div>
<div class="text-right col-xs-2">
    <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/web/start/');?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
</div>
</h5>
<div class="clearfix"></div>
<?php if (count(PickWeb::model()->findAllByAttributes(array('web_order_id'=>$web_order->id,'status'=>0))) == 0):?>
<hr>
<div class="col-xs-12 row"><a href="<?=Yii::app()->createUrl('/web/close/'.$web_order->id);?>" class="btn btn-success btn-xlg col-xs-12" onclick="return confirm('Da li ste sigurni?');">ZAVRŠI NALOG</a></div>
<hr>
<?php endif; ?>
<div class="clearfix"></div>
<?php


$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model->search(),
    'id' => 'pick-list',
    'template' => '{pager}{items}{pager}',
    'itemView' => '_pick',
    'viewData' => array('web_order' => $web_order),

    'pager' => array('class' => 'CLinkPager',
        'header' => '',
        'nextPageLabel' => Yii::t('app', ">"),
        'prevPageLabel' => Yii::t('app', '<'),
        'lastPageLabel' => false,
        'firstPageLabel' => false,
        'htmlOptions' => array(
            'class' => 'pagination',
        )),
));