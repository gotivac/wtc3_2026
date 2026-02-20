<h5>
    <div class="text-left col-xs-10">
        <?= $order->order_number;?> &bull; <?=$order->client->title;?> &bull; <?=Yii::t('app',ucfirst($model->id));?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/outbound/pickType/'.$order->id);?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>
<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'products-filter-form',
    'type'=> 'vertical',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div class="row">
    <div class="col-xs-6">
        <?php if (isset($_POST['unpicked'])) {
            $filter = $_POST['unpicked'];
        } else {
            $filter = false;
        }
        ?>
        <div class="form-group">
            <?php echo CHtml::dropDownList('unpicked',$filter,array(false=>'Svi proizvodi',true => 'Preostali'),array('class'=>'form-control','onchange'=>'$("#products-filter-form").submit();')); ?>
        </div>
    </div>
    <div class="col-xs-6">
        <?php if (isset($_POST['street'])) {
            $street = $_POST['street'];
        } else {
            $street = false;
        }
        ?>
        <div class="form-group">
            <?php echo CHtml::dropDownList('street',$street,CHtml::listData(Sloc::model()->findAll(),'sloc_street','sloc_street'),array('class'=>'form-control','onchange'=>'$("#products-filter-form").submit();','empty'=>'')); ?>
        </div>
    </div>


</div>

</div>
<?php $this->endWidget(); ?>


<?php


$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model,
    'id' => 'activity-list',
    'template' => '{pager}{items}{pager}',
    'itemView' => '_palett',
    'viewData' => array('order' => $order),

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