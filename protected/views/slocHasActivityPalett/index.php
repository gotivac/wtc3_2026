<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Occupied SLOCs') => array('index'),
    Yii::t('app', 'List'),
);

?>
<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('sloc-has-activity-palett-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>
<div class="search-form">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div>
<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'sloc-has-activity-palett-grid',
    'dataProvider' => $model->search(),
    // 'enableSorting' => false,
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),

    'mergeColumns' => array('sloc_code'),
    'filter' => null,
    'rowCssClassExpression' => function ($index, $data) {

        $class = '';

        $picked = Pick::model()->findAll(array('condition' => 'sscc_source = "' . $data->sscc . '" AND status=0','order'=>'id DESC','limit'=>1));



        if (!empty($picked)) {
            $pick = $picked[0];
            if ($pick->pick_type == 'palett') {
                $class .= 'text-muted alert-danger';
            } else {
                $class .= 'text-muted alert-warning';
            }
        }


        return $class;

    },
    'columns' => array(
        'sloc_code',
        'sscc',


        array(
            'name' => 'storage_type_id',
            'type' => 'raw',
            'class' => 'application.extensions.editable.EditableColumn',
            'value' => '$data->storageType ? $data->storageType->title : ""',
            'editable' => array(
                'title' => Yii::t('app', 'Storage Type'),
                'type' => 'select',
                'source' => CHtml::listData(StorageType::model()->findAll(),'id','title'),
                'url' => $this->createUrl('slocHasActivityPalett/ajaxUpdate'),
                // 'success' => 'function(){location.href=location.href+"?tab=1";}'
            ),
            'visible' => $this->user->isAdmin()


        ),
        array(
                'name' => 'storage_type_id',
                'value' => '$data->storageType ? $data->storageType->title : ""',
            'visible' => !$this->user->isAdmin()
        ),

        array(
            'header' => Yii::t('app', 'Content'),
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';
                foreach ($data->activityPalett->hasProducts as $item) {
                    $result .= ($item->product) ? CHtml::link($item->product->internal_product_number . ' - ' . $item->product->title, '', array('onclick' => 'showHistory(' . $item->id . ',"' . $item->product->product_barcode . ' - ' . $item->product->internal_product_number . ' - ' .$item->product->title.'")')) . '<br>' : '';
                }
                $result = rtrim($result, '<br>');
                return $result;
            },
            'htmlOptions' => array('class' => 'col-md-4'),
        ),
        array(
            'header' => Yii::t('app', 'Product Barcode'),
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';
                foreach ($data->activityPalett->hasProducts as $item) {
                    $result .= ($item->product) ? $item->product->product_barcode . '<br>' : '';
                }
                $result = rtrim($result, '<br>');
                return $result;
            },

        ),
        array(
            'header' => Yii::t('app', 'Delivery Number'),
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';
                foreach ($data->activityPalett->hasProducts as $item) {
                    $result .= $item->delivery_number . '<br>';
                }
                $result = rtrim($result, '<br>');
                return $result;
            },

        ),
        array(
            'header' => Yii::t('app', 'Quantity'),
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';

                foreach ($data->activityPalett->hasProducts as $item) {
                    $picked = Pick::model()->findByAttributes(array('sscc_source' => $data->sscc, 'status' => 0));
                    if ($picked) {
                        $result .= number_format($item->content['quantity'],0,',','.') . ' (' . number_format($item->stockQuantity,0,',','.') . ')<br>';
                    } else {
                        $result .= CHtml::link(number_format($item->content['quantity'], 0, ',', '.'), '', array('onclick' => 'updateQuantity(' . $item->id . ')')) . '<br>';
                    }
                }
                $result = rtrim($result, '<br>');
                return $result;
            },
            'htmlOptions' => array('class' => 'text-right', 'style' => 'width:120px'),
            'headerHtmlOptions' => array('class' => 'text-right')
        ),
        array(
            'header' => Yii::t('app', 'Volume'),
            'type' => 'raw',
            'value' => function ($data) {
                $result = '';

                foreach ($data->activityPalett->hasProducts as $item) {
                    $result .= number_format($item->volume, 2, ',', '.') . '<br>';
                    // $result .= number_format($item->volume, 2, ',', '.') . ' cm<sup>3</sup><br>';
                    /*
                    $picked = Pick::model()->findByAttributes(array('sscc_source' => $data->sscc, 'status' => 0));
                    if ($picked) {
                        $result .= number_format($item->content['quantity'],0,',','.') . ' (' . number_format($item->stockQuantity,0,',','.') . ')<br>';
                    } else {
                        $result .= CHtml::link(number_format($item->content['quantity'], 0, ',', '.'), '', array('onclick' => 'updateQuantity(' . $item->id . ')')) . '<br>';
                    }
                    */
                }
                return rtrim($result, '<br>');
            },
            'htmlOptions' => array('class' => 'text-right', 'style' => 'width:120px'),
            'headerHtmlOptions' => array('class' => 'text-right')
        ),
        array(
            'header' => Yii::t('app', 'Order'),
            'type'=>'raw',
            'value' => '$data->activityPalett && $data->activityPalett->activityOrder ? CHtml::link($data->activityPalett->activityOrder->order_number,Yii::app()->createUrl("/order/".$data->activityPalett->activity->orderRequest->id)) : ""',
        ),
        array(
            'name' => 'created_dt',
            'type' => 'raw',
            'value' => '$data->activityPalett ? date("d.m.Y H:i",strtotime($data->activityPalett->created_dt)) : ""',
        ),


        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => ' {split} {sticker} {view}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs view'
                    )
                ),

                'split' => array(
                    'label' => '<i class="glyphicon glyphicon-indent-left"></i>',
                    'url' => 'Yii::app()->createUrl("activityPalett/resSplit/".$data->activity_palett_id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Split'),
                        'onclick' => 'return confirm("'.Yii::t('app','Are you sure you want to split?') . '");'
                    ),
                    'visible' => function($index,$data){

                        $picked = Pick::model()->findByAttributes(array('sscc_source' => $data->sscc, 'status' => 0));
                        if ((!$picked || $picked->pick_type == 'product') && $this->user->isAdmin()) {
                            return true;
                        }
                        return false;
                        },
                ),
                'sticker' => array(
                    'label' => '<i class="glyphicon glyphicon-barcode"></i>',
                    'url' => 'Yii::app()->createUrl("/activity/resSticker/".$data->activity_palett_id)',
                    'options' => array(
                        'class' => 'btn btn-xs view',
                        'title' => Yii::t('app', 'Sticker'),
                    )
                ),
                'update' => array(
                    'label' => Yii::t('app', 'Update'),
                    'options' => array(
                        'class' => 'btn btn-xs update'
                    )
                ),
                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'options' => array(
                        'class' => 'btn btn-xs delete'
                    ),

                )
            ),
        ),
    ),
)); ?>
<?php
/**
 *
 *
 *          UPDATE QUANTITY MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "updateQuantity",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title1"><?= Yii::t('app', 'Update Quantity'); ?></h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_update_quantity', array('activity_palett_has_product_log' => $activity_palett_has_product_log)); ?>
</div>

<?php $this->endWidget(); ?>

<?php
/**
 *
 *
 *          SHOW HISTORY MODAL
 */

$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "showHistory",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title2-history"></h4>
</div>
<div class="modal-body" id="history">

</div>

<?php $this->endWidget(); ?>
<script>


    function updateQuantity(id) {

        <?php if (!$this->user->isAdmin()):?>
        return false;
        <?php endif;?>

        $.ajax({
            url: '<?= Yii::app()->createUrl("slocHasActivityPalett/ajaxGetActivityPalettHasProduct");?>',
            dataType: 'json',
            type: 'get',
            data: {'id': id},
            success: function (data) {
                if (typeof data.id != "undefined") {

                    $('#ActivityPalettHasProductLog_activity_palett_id').val(data.activity_palett_id);
                    $('#ActivityPalettHasProductLog_activity_palett_has_product_id').val(data.id);
                    $('#ActivityPalettHasProductLog_sscc').val(data.sscc);
                    $('#ActivityPalettHasProductLog_product_id').val(data.product_id);
                    $('#ActivityPalettHasProductLog_product_info').val(data.product_info);
                    $('#ActivityPalettHasProductLog_quantity').val(data.quantity);
                    $('#ActivityPalettHasProductLog_packages').val(data.packages);
                    $('#ActivityPalettHasProductLog_units').val(data.units);
                    $('#updateQuantity').modal('show');
                }
            }
        });
    }

    function showHistory(id,title) {
        $('#modal-title2-history').html(title);
        $.ajax({
            url: '<?= Yii::app()->createUrl("slocHasActivityPalett/ajaxGetActivityPalettHasProductHistory");?>',
            dataType: 'html',
            type: 'get',
            data: {'id': id},
            success: function (data) {
                if (data != '') {
                    $('#history').html(data);

                } else {
                    $('#history').html('ERROR!');
                }
                $('#showHistory').modal('show');
            }
        });
    }

    $(document).ready(function () {
        var modal = ($('#showHistory').children()[0]);
        modal.style.width = '1200px';
    });
</script>