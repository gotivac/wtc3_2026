<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Web Orders') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array('index')),
    
);
?>

<div class="alert-placeholder"></div>
<div class="col-md-6">

    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'order_klett_id',
            'order_number',
            'client_id',
            array(
                'name' => 'customer_data',
                'type' => 'raw',
                'value' => function ($model) {
                    $arr = json_decode($model->customer_data,true);

                    $str = '';
                    foreach ($arr as $k => $v) {
                        $str .= '<b>' . $k . '</b>: ' . (is_array($v) ? "" : $v) . '<br>';
                    }
                    return $str;
                }
            ),

            'status',

            'created_dt',

            'updated_dt',
        ),
    )); ?>
</div>