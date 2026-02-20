<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'supplier-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
));
?>
<div class="col-md-2 well text-center">
    <h4><?= Yii::t('app','Add Supplier');?></h4>

    <?php $client_condition = empty($model->suppliers) ? 'id != ' . $model->id : 'id NOT IN (' . implode(',', array_merge(ClientHasSupplier::model()->getSupplierIds($model->id),array($model->id))) . ')'; ?>

    <div class="form-group" style="padding: 10px">
        <?php echo CHtml::hiddenField('ClientHasSupplier[client_id]',$model->id); ?>
        <?php echo CHtml::dropDownList('ClientHasSupplier[supplier_id]', '', CHtml::listData(Client::model()->findAll(array('condition' => $client_condition)), 'id', 'title'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
    <div class="form-group">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => Yii::t('app', 'Add Supplier'),
        ));
        ?>

    </div>
</div>
<?php $this->endWidget(); ?>


<div class="col-md-10">


    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'supplier-grid',
        'dataProvider' => $client_has_suppliers->search(),
        'summaryText' => false,

        'filter' => null,
        'columns' => array(

            array(
                'header' => Yii::t('app','Suppliers'),
                'name' => 'client_id',
                'value' => '$data->supplier->title'
            ),


            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{delete}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'label' => Yii::t('app', 'Update'),
                        'options' => array(
                            'class' => 'btn btn-xs update'
                        )
                    ),
                    'delete' => array(
                        'label' => Yii::t('app', 'Delete'),
                        'url' => 'Yii::app()->createUrl("systemSettings/client/ajaxRemoveSupplier/id/".$data->id)',

                        'options' => array(

                            'class' => 'btn btn-xs delete'
                        ),

                    )
                ),
            ),
        ),
    )); ?>

</div>

