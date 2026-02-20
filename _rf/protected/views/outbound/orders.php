
<h5 class="text-center">
    OUTBOUND NALOZI
</h5>
<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'orders-filter-form',
    'type'=> 'vertical',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div class="row">
<div class="col-xs-12">
    <?php if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
    } else {
        $filter = '';
    }
    ?>
<div class="form-group">
    <?php echo CHtml::dropDownList('filter',$filter,array(''=>'Svi nalozi','0' => 'Pikovanje','1'=>'Gate Out'),array('class'=>'form-control','onchange'=>'$("#orders-filter-form").submit();')); ?>
    </div>
</div>
    <div class="col-xs-9">
        <div class="form-group">
    <?php echo CHtml::textField('order_number',$order_number,array('class'=>'form-control','placeholder'=>'Broj naloga')); ?>
    </div>
    </div>
    <div class="col-xs-3">
        <button type="submit" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
    </div>

</div>

</div>
<?php $this->endWidget(); ?>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model,
    'id' => 'order-list',
    'template' => '{items}{pager}',
    'itemView' => '_order',


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
