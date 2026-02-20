<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Sloc Has Products') => array('index'),
    Yii::t('app', 'Stock'),
);


?>
<div class="col-md-12 text-right">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?= CHtml::link('Excel', Yii::app()->createUrl('slocHasProduct/resExportExcel'),array('class' => 'btn btn-success')); ?>
    </div>
</div>
<div class="clearfix"></div>
<hr>

<?php $this->widget('ext.groupgridview.BootGroupGridView', array(
    'id' => 'sloc-has-product-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    'mergeColumns' => array('sloc_code'),
    'filter' => $model,
    'columns' => array(

        'sloc_code',
        array(
            'name' => 'product_search',
            'type' => 'raw',
            'value' => '$data->product ? CHtml::link($data->product->internal_product_number . " - " . $data->product->title, "", array("onclick" => "showHistory(" . $data->id . ")")) : ""',


        ),

        'product_barcode',
        array(
            'name' => 'quantity',
            'type' => 'raw',
            'value' => 'CHtml::link(number_format($data->realQuantity, 0, ",", "."), "", array("onclick" => "updateQuantity(" . $data->id . ")"))',
            'htmlOptions' => array('class' => 'text-right'),
        ),


        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'View'),
                    'options' => array(
                        'class' => 'btn btn-xs view'
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
                    'visible' => (Yii::app()->params['adminDelete']),
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

    <?php  $this->renderPartial('_update_quantity', array('sloc_has_product_log' => $sloc_has_product_log)); ?>
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
    <h4 id="modal-title2"></h4>
</div>
<div class="modal-body" id="history">

</div>

<?php $this->endWidget(); ?>


<script>
    function updateQuantity(id) {
<?php if (!$this->user->isAdmin()):?>
        return false;
        <?php endif; ?>
        $.ajax({
            url: '<?= Yii::app()->createUrl("slocHasProduct/ajaxGetSlocHasProduct"); ?>',
            dataType: 'json',
            type: 'get',
            data: {'id': id},
            success: function (data) {
                if (typeof data.id != "undefined") {

                    $('#SlocHasProductLog_sloc_id').val(data.sloc_id);
                    $('#SlocHasProductLog_sloc_has_product_id').val(data.id);
                    $('#SlocHasProductLog_sloc_code').val(data.sloc_code);
                    $('#SlocHasProductLog_product_id').val(data.product_id);
                    $('#SlocHasProductLog_product_info').val(data.product_info);
                    $('#SlocHasProductLog_quantity').val(data.quantity);

                    $('#updateQuantity').modal('show');
                }
            }
        });
    }

    function showHistory(id) {
        $.ajax({
            url: '<?= Yii::app()->createUrl("slocHasProduct/ajaxGetSlocHasProductHistory"); ?>',
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