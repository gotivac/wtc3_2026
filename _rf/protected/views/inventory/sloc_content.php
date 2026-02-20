<h5>
    <div class="row">
    <div class="text-left col-xs-10">
        <b>POPIS</b> - <?= $sloc->sloc_code;?>
    </div>
    <div class="text-right col-xs-2">
        <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/inventory');?>"><i class="glyphicon glyphicon-arrow-left"></i></a>
    </div>
    </div>
</h5>
<?php
$this->widget(
    'booster.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(

            array(
                'label' => Yii::t('app', 'Paletts'),
                'content' => $this->renderPartial('_paletts', array('model' => $sloc_has_activity_paletts), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0)) ? true : false,


            ),

            array(
                'label' => Yii::t('app', 'Products'),
                'content' => $this->renderPartial('_products', array('model' => $sloc_has_products,), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1) ? true : false,


            ),


        ),
    )
);

?>
