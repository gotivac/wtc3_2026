
<div class="col-md-4 col-md-offset-8">

    <table class="items table table-bordered">
        <tr><td>Pušteno naloga</td><td class="text-right"><?=number_format($total_orders,0,',','.');?></td></tr>
        <tr><td>Urađeno artikala</td><td class="text-right"><?=number_format($completed_products,0,',','.');?></td></tr>
        <tr><td>Urađeno komada</td><td class="text-right"><?=number_format($completed_quantity,0,',','.');?></td></tr>
    </table>

</div>
<div class="clearfix"></div>
<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'orders-grid',
    'dataProvider' => $model[1],
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),


    'columns' => array(
        array(
            'header' => 'No.',
            'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize) . "."',
            'htmlOptions' => array('class' => 'text-right', 'style' => 'width:60px'),
            'headerHtmlOptions' => array('class' => 'text-right', 'style' => 'width:60px'),
        ),
        array(
            'header' => 'Vreme dodeljivanja',
            'value' => 'date("d.m.Y H:i",strtotime($data->created_dt))',
        ),
        array(
            'header' => 'Početak rada pikera',
            'value' => function ($data) {
                $sql = 'SELECT activity_palett_has_product.created_dt FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                return $result ? date('d.m.Y H:i', strtotime($result)) : "";
            }
        ),
        array(
            'name' => 'order_number',
            'header' => 'Broj naloga',
            'type' => 'raw',
            'value' => '$data->orderClient ? CHtml::link($data->order_number, Yii::app()->createUrl("/order/".$data->orderClient->orderRequest->id),array("target"=>"_blank")) : $data->order_number',
            'htmlOptions' => array('class' => 'col-md-1')
        ),
        array(
            'header' => 'Vrsta aktivnosti',
            'value' => '$data->activity->activityType->title',
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'header' => 'Broj zadataih artikala u nalogu',
            'value' => function ($data) {
                $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                return $result ? number_format(count($result),0,',','.') : 0;
            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'header' => 'Broj zadataih komada u nalogu',
            'value' => function ($data) {
                $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                return $result ? number_format($result,0,',','.') : 0;
            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'header' => 'Broj urađenih / primljenih artikala',
            'value' => function ($data) {
                $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                return $result ? number_format(count($result),0,',','.') : 0;
            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),

        array(
            'header' => 'Broj urađenih / primljenih komada',
            'value' => function ($data) {
                $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                return $result ? number_format($result,0,',','.') : 0;
            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),

        array(
            'header' => 'Broj preostalih artikala',
            'value' => function ($data) {
                $sql = 'SELECT DISTINCT product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $total = $result ? count($result)  : 0;

                $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $completed = $result ? count($result) : 0;

                return number_format($total - $completed,0,',','.');
            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),

        array(
            'header' => 'Broj preostalih komada',
            'value' => function ($data) {

                $sql = 'SELECT SUM(quantity) product_id FROM activity_order_product WHERE activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                $total = $result;

                $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id;
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                $completed = $result;
                return number_format($total - $completed,0,',','.');

            },
            'htmlOptions' => array('class' => 'text-right'),
            'headerHtmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'header' => 'Piker',
            'value' => function($data) {
                $sql = 'SELECT activity_palett_has_product.created_user_id FROM activity_palett_has_product JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id WHERE activity_palett.activity_order_id = ' . $data->id . ' ORDER BY activity_palett_has_product.created_dt ASC LIMIT 0,1';
                $result = Yii::app()->db->createCommand($sql)->queryScalar();
                $user = User::model()->findByPk($result);
                if ($user) {
                    return $user->name;
                } else {
                    return 'Korisnik ID: ' . $result;
                }
                return '';

            }
        ),
        array(
            'header' => 'Trajanje rada',
            'value' => function($data) {
                $start = new DateTime($data->created_dt);
                $end = new DateTime(date('Y-m-d H:i:s'));
                $diff = $end->diff($start);
                $hours = ($diff->format("%a") * 24) + $diff->format("%h");
                $minutes = $diff->format("%I");

                return $hours.':'.$minutes;

            },
            'headerHtmlOptions' => array('class' => 'text-center'),
        )


    ),
)); ?>