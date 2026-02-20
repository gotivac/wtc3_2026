<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Dashboard') => array('index'),

);

$this->menu = array(array('label' => Yii::t('app', 'Excel'), 'url' => array('resExcel')),
);

?>
<?php

$this->widget(
    'booster.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(

            array(
                'label' => Yii::t('app', 'Inbound'),
                'content' => $this->renderPartial('_inbound_orders', array('model' => $model,'total_orders' => $total_inbound_orders,'completed_products' => $completed_inbound_products,'completed_quantity' => $completed_inbound_quantity), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0)) ? true : false,
                 'url' => '?tab=0',

            ),
            array(
                'label' => Yii::t('app', 'Outbound'),
                'content' => $this->renderPartial('_outbound_orders', array('model' => $model,'total_orders' => $total_outbound_orders,'completed_products' => $completed_outbound_products,'completed_quantity' => $completed_outbound_quantity), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1) ? true : false,
                 'url' => '?tab=1',

            ),

            array(
                'label' => Yii::t('app', 'Web Orders'),
                'content' => $this->renderPartial('_web_orders',array('model'=>$model,'total_web_orders' => $total_web_orders,'completed_web_products' => $completed_web_products,'completed_web_quantity' => $completed_web_quantity), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 2 ) ? true : false,
                'url' => '?tab=2',


            ),


        ),
    )
);

?>


<script>
    window.setTimeout( function() {
        window.location.reload();
    }, 120000);
</script>
