<h5>
    <div class="col-xs-10 text-left vertical-center">
        SSCC: <?= $activity_palett->sscc; ?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/info');?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
</h5>
<div class="clearfix"></div>
<hr>
<?php if ($activity_palett->inSloc):?>
<h5>Paleta se nalazi u SLOC: <?=$activity_palett->inSloc->sloc_code;?></h5>
<?php elseif ($activity_palett->isLoaded()):?>
<h5>Paleta je utovarena. <?= $activity_palett->activity ? $activity_palett->activity->gate->title : "";?></h5>
<?php else: ?>
<h5>Paleta je u gate zoni. <?= $activity_palett->activity ? $activity_palett->activity->gate->title : "";?></h5>
<?php endif; ?>
<?php

    $this->widget('ext.groupgridview.BootGroupGridView', array(
        'id' => 'order-product-grid',
        'dataProvider' => $activity_palett_has_product->search(),
        'hideHeader' => true,
        'summaryText' => false,

        'filter' => null,


        'columns' => array(

            array(
                'header' => 'Proizvod',
                'type' => 'raw',
                'value' => '$data->product_barcode',


            ),
            array(
                'header' => 'Količina',
                'type' => 'raw',
                'value' => '$data->content["quantity"]',
                'htmlOptions' => array('class' => 'text-right')

            ),


        ),
    ));
