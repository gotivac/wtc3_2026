<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Activities') => array('index'),
    $model->activity->activityType->title . ' - ' . $model->activity->location->title . ' - ' . $model->activity->gate->title => array('/activity/update/' . $model->activity->id),
    $model->order_number,
    Yii::t('app', 'Delivery Notice Cut'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'Back'), 'url' => array('/activity/update/' . $model->activity->id . '?tab=1')),
);

?>
<?php if (empty($cuts)):?>
    <p>Nema utovarenih paleta.</p>
<?php endif;?>
<?php foreach ($cuts as $k => $group): ?>
    <div class="text-right">
        <form method="post" action=""><input type="hidden" name="group[]" value="<?= $k; ?>">
            <button type="submit" class="btn btn-success btn-small">Štampaj otpremnicu</button>
        </form>
        </h4>
    </div>
    <?php $this->widget('booster.widgets.TbGridView', array(
        'enableSorting' => false,
        'id' => 'activity-paletts-grid-' . $k,
        'dataProvider' => $group,
        'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
        'filter' => null,

        'columns' => array(
            array(
                'header' => 'No.',
                'value' => '$row+ 1 + ($this->grid->dataProvider->pagination->currentPage
                    * $this->grid->dataProvider->pagination->pageSize)',
                'htmlOptions' => array('class' => 'text-right', 'style' => 'width:60px'),
                'headerHtmlOptions' => array('class' => 'text-right', 'style' => 'width:60px'),
            ),

            array(
                'name' => 'sscc',
                'header' => 'SSCC',
            ),

            array(
                'header' => 'Utovareno',
                'value' => '$data->picks ? $data->picks[0]->updated_dt : "?"',

            ),
            array(
                'header' => Yii::t('app', 'Products'),
                'type' => 'raw',
                'value' => function ($data) {
                    $result = '<table class="table-bordered table-condensed" style="width: 100%">';
                    $row = 1;
                    foreach ($data->hasProducts as $activity_palett_has_product) {

                        $result .= '<tr><td style="width:5%;text-align: right">' . $row . '.</td><td style="width:15%">' . $activity_palett_has_product->product->internal_product_number . '</td><td style="width:60%">' . $activity_palett_has_product->product->title . '</td><td style="width:16%">' . $activity_palett_has_product->product->product_barcode . '</td><td class="text-right">' . $activity_palett_has_product->quantity . '</td></tr>';
                        $row++;
                    }
                    $result .= '</table>';
                    return $result;
                },
                'htmlOptions' => array('class' => 'col-md-6')
            ),


        ),
    ),
    ); ?>

    <hr>

<?php endforeach; ?>

