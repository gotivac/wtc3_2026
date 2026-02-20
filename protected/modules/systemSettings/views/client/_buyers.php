<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'buyer-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
));
?>
<div class="col-md-2 well text-center">
    <h4><?= Yii::t('app','Add Buyer');?></h4>

    <?php $client_condition = empty($model->buyers) ? 'id != ' . $model->id : 'id NOT IN (' . implode(',', array_merge(ClientHasSupplier::model()->getBuyerIds($model->id),array($model->id))) . ')'; ?>

    <div class="form-group" style="padding:10px">
        <?php echo CHtml::hiddenField('ClientHasBuyer[supplier_id]',$model->id); ?>
        <?php echo CHtml::dropDownList('ClientHasBuyer[client_id]', '', CHtml::listData(Client::model()->findAll(array('condition' => $client_condition)), 'id', 'title'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
    <div class="form-group">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => Yii::t('app', 'Add Buyer'),
        ));
        ?>

    </div>
</div>
<?php $this->endWidget(); ?>


<div class="col-md-10">


    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'buyer-grid',
        'dataProvider' => $client_has_buyers->search(),
        'summaryText' => false,

        'filter' => null,
        'columns' => array(

            array(
                    'header' => Yii::t('app','Buyers'),
                'name' => 'client_id',
                'value' => '$data->buyer->title'
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

