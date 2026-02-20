<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Web Orders') => array('index'),
    Yii::t('app', 'List'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
    array('label' => Yii::t('app', 'Excel'), 'url' => array('resExportExcel'),'visible' => $this->user->canCreate('webOrder') == null ? false : true),
);

?>

<?php
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
        'error' => array('closeText' => Yii::t('app', 'Error')),
    ),
));
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'web-order-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'filter' => $model,
    'columns' => array(

        'order_number',
        array(
            'name' => 'client_id',
            'value' => '$data->client ? $data->client->title : ""',
            'filter' => false,
        ),
        array(
            'name' => 'customer_data',
            'filter' => false,
            'type' => 'raw',
            'value' => function ($data) {
                $customer_data = json_decode($data->customer_data, true);
                $result = '';


                if (is_array($customer_data)) {
                    foreach ($customer_data as $k => $v) {
                        if (is_array($v)) {
                            $v = implode(',', $v);
                        }
                        $result .= '<b>' . $k . ':</b> ' . $v . '<br>';
                    }
                }
                return $result;
            }
        ),
        array(
            'header' => Yii::t('app', 'Products'),
            'type' => 'raw',
            'value' => function ($data) {
                if (!empty($data->webOrderProducts)) {
                    $result = '<ul>';
                    $quantity = 0;
                    foreach ($data->webOrderProducts as $orderProduct) {
                        $result .= '<li>' . $orderProduct->product->title . ': ' . $orderProduct->quantity . '</li>';
                        $quantity += $orderProduct->quantity;
                    }
                    $result .= '</ul>';

                    $result.= '<b>' . Yii::t('app','Ukupno').': ' . $quantity . ' komada</b>';
                } else {
                    $result = CHtml::link(Yii::t('app', 'Add Products'), '/activityOrderProduct/create/' . $data->id);
                }
                return $result;
            },
        ),
        array(
            'header' => Yii::t('app', 'Product Barcode'),
            'type' => 'raw',
            'value' => function ($data) {
                if (!empty($data->webOrderProducts)) {
                    $result = '<ul>';
                    foreach ($data->webOrderProducts as $orderProduct) {
                        $result .= '<li>' . $orderProduct->product->product_barcode . '</li>';
                    }
                    $result .= '</ul>';
                }
                return $result;
            },
        ),
        'load_list',
        'delivery_type',


        array(
            'name' => 'status',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 0, 'title' => Yii::t('app', 'No')), array('id' => 1, 'title' => Yii::t('app', 'Yes'))), 'id', 'title'),
            'value' => '($data->status) ? "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span></span>" : "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span></span>"',
            'htmlOptions' => array('class' => 'text-center col-lg-1'),
        ),
        array(
            'name' => 'created_dt',
            'value' => 'date("Y-m-d H:i",strtotime($data->created_dt))',
        ),
        array(
            'header' => Yii::t('app','Spremljen'),
            'name' => 'updated_dt',
            'value' => '$data->updated_dt != NULL ? date("Y-m-d H:i",strtotime($data->updated_dt)) : ""',
        ),
        array(
            'htmlOptions' => array('nowrap' => 'nowrap', 'class' => 'text-right'),
            'template' => '{close} {delete}',
            'class' => 'booster.widgets.TbButtonColumn',

            'buttons' => array(

                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),
                    'visible' => 'empty($data->activity) && (Yii::app()->user->roles == "administrator" OR Yii::app()->user->roles == "superadministrator")',

                ),
                'close' => array(
                    'label' => Yii::t('app', 'Zatvori'),
                    'url' => 'Yii::app()->createUrl("/webOrder/resClose/" . $data->id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'onclick' => 'return confirm("Da li ste sigurni da zatvarate nalog?");'
                    ),
                    'visible' => '$data->status == 0 && (Yii::app()->user->roles == "administrator" OR Yii::app()->user->roles == "superadministrator")',

                )
            ),

        ),
    )
)); ?>
