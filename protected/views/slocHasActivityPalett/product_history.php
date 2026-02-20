<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Product History') => array('resProductHistory'),
    Yii::t('app', 'List'),
);

?>

<div class="search-form">
    <?php

    $this->renderPartial('_search_history', array(
        'model' => $product,
    ));
    ?>
</div>

<?php if (!empty($model)): ?>
    <div class="col-md-6 col-md-offset-3">
        <table class="table table-bordered">
            <tr>
                <td colspan="5" class="text-center">
                    <h3><?= '<span class="label label-danger">' . $model['product']->product_barcode . '</span> <span class="label label-primary">' . $model['product']->internal_product_number . '</span> <span class="label label-primary">' . $model['product']->title; ?>
                        <span></span></h3>
                </td>
            </tr>
            <tr>
                <th class="text-center">ULAZ</th>
                <th class="text-center">IZLAZ</th>
                <th class="text-center">WEB</th>
                <th class="text-center">POPIS</th>
                <th class="text-center">STANJE</th>
            </tr>
            <tr>
                <td class='text-right'><?= $model['inq']; ?></td>
                <td class='text-right'><?= $model['outq']; ?></td>
                <td class='text-right'><?= $model['webq']; ?></td>
                <td class='text-right'><?= $model['corrq']; ?></td>
                <td class='text-right'><?= $model['inq'] - ($model['outq'] + $model['webq']) + $model['corrq']; ?></td>

            </tr>
        </table>

    </div>
    <div class="clearfix"></div>
    <hr>

    <?php if (!empty($model['corrq'])): ?>
        <div class="col-md-12">
            <h4>Popis</h4>
            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th>Datum</th>
                    <th>SSCC</th>
                    <th>Količina</th>
                    <th>Razlog</th>
                    <th>Korisnik</th>
                </tr>
                <?php $r = 1; ?>
                <?php foreach ($model['corr'] as $row): ?>
                    <tr>
                        <td class="text-right"><?= $r; ?>.</td>
                        <td><?= date('d.m.Y', strtotime($row['datetime'])); ?></td>
                        <td><?= $row['sscc']; ?></td>
                        <td class="text-right"><?= $row['quantity']; ?></td>
                        <td class="text-right"><?= $row['reason']; ?></td>
                        <td class="text-right"><?= $row['username']; ?></td>
                    </tr>
                    <?php $r++; ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><b><?= $model['corrq']; ?></b></td>
                    <td></td>
                    <td></td>

                </tr>
            </table>


        </div>
        <div class="clearfix"></div>
        <hr>
    <?php endif; ?>
    <div class="col-md-4">
        <h4>Ulazi</h4>
        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>Datum</th>
                <th>Broj naloga</th>
                <th>Najavljena količina</th>
                <th>Primljena količina</th>
            </tr>
            <?php $r = 1; ?>
            <?php foreach ($model['in'] as $row): ?>
                <tr>
                    <td class="text-right"><?= $r; ?>.</td>
                    <td><?= date('d.m.Y', strtotime($row['datetime'])); ?></td>
                    <td><?= $row['order_number']; ?></td>
                    <td class="text-right"><?= $row['quantity']; ?></td>
                    <td class="text-right"><?= $row['real_quantity'] == '?' ? '<span class="badge">?</span>' : $row['real_quantity']; ?></td>
                </tr>
                <?php $r++; ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><b><?= $model['inq']; ?></b></td>
                <td class="text-right"><b><?= $model['inqreal']; ?></b></td>
            </tr>
        </table>
        <hr>

        <h4>Zalihe:</h4>
        <?php if ($activity_palett_has_product):?>
        <?php
        $this->widget('ext.groupgridview.BootGroupGridView', array(
            'id' => 'order-product-grid',
            'dataProvider' => $activity_palett_has_product,
            'hideHeader' => true,
            'summaryText' => false,
            'type' => 'bordered',

            'filter' => null,


            'columns' => array(



                array(
                    'header' => 'Lokacija',
                    'type' => 'raw',
                    'value' => function ($data) {
                        if ($data->activityPalett->inSloc) {
                            return $data->sscc . " u <b>" . $data->activityPalett->inSloc->sloc_code . "<b>";
                        }
                        $pick = Pick::model()->findByAttributes(array('sscc_destination' => $data->activityPalett->sscc, 'product_id' => $data->product_id));

                        if ($pick === null) {
                            return $data->sscc . " u Gate IN";
                        }
                        return $data->sscc;
                    },
                    ),

                    array(
                        'header' => 'Količina',
                        'type' => 'raw',
                        'value' => '$data->realQuantity',
                        'htmlOptions' => array('class' => 'text-right col-md-2'),


                    ),


                ),
            ));
        ?>
<?php endif;?>
        <?php if ($sloc_has_product):?>
        <?php
        $this->widget('ext.groupgridview.BootGroupGridView', array(
            'id' => 'order-product-grid',
            'dataProvider' => $sloc_has_product->search(),
            'hideHeader' => true,
            'summaryText' => false,

            'filter' => null,
            'type' => 'bordered',


            'columns' => array(


                'sloc_code',
                array(
                    'header' => 'Količina',
                    'type' => 'raw',
                    'value' => '$data->realQuantity',
                    'htmlOptions' => array('class' => 'text-right col-md-2')

                ),


            ),
        ));


        ?>
        <?php endif;?>
    </div>
    <div class="col-md-4">
        <h4>Komercijalni nalozi</h4>
        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>Datum</th>
                <th>Broj naloga</th>
                <th>Tražena količina</th>
                <th>Pikovana količina</th>
            </tr>
            <?php $r = 1; ?>
            <?php foreach ($model['out'] as $row): ?>
                <tr>
                    <td class="text-right"><?= $r; ?>.</td>
                    <td><?= date('d.m.Y', strtotime($row['datetime'])); ?></td>
                    <td><?= $row['order_number']; ?></td>
                    <td class="text-right"><?= $row['quantity']; ?></td>
                    <td class="text-right"><?= $row['real_quantity']; ?></td>
                </tr>
                <?php $r++; ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><b><?= $model['outq']; ?></b></td>

            </tr>
        </table>

    </div>
    <div class="col-md-4">
        <h4>WEB nalozi</h4>
        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>Datum</th>
                <th>Broj naloga</th>
                <th>Tražena količina</th>
                <th>Pikovana količina</th>
            </tr>
            <?php $r = 1; ?>
            <?php foreach ($model['web'] as $row): ?>
                <tr>
                    <td class="text-right"><?= $r; ?>.</td>
                    <td><?= date('d.m.Y', strtotime($row['datetime'])); ?></td>
                    <td><?= $row['order_number']; ?></td>
                    <td class="text-right"><?= $row['quantity']; ?></td>
                    <td class="text-right"><?= $row['real_quantity']; ?></td>
                </tr>
                <?php $r++; ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><b><?= $model['webq']; ?></b></td>

            </tr>
        </table>
    </div>


<?php endif; ?>


