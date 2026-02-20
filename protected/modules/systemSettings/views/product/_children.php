<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'product-children-form',
    'type' => 'vertical',
    'enableAjaxValidation' => false,
));
?>
<div class="col-md-3 well">
    <h4><?= Yii::t('app', 'Add Child'); ?></h4>
    <?php
    $unused_product_ids = implode(',', array_diff(Product::model()->findAllIdsByClientId($model->client_id), array($model->id)));

    if ($unused_product_ids != '') {
        $product_condition = 'id IN (' . $unused_product_ids . ')';
    } else {
        $product_condition = 'id = 0';
    }
    ?>


    <div class="form-group" style="padding:10px">
        <?php echo $form::hiddenField($product_has_child, 'product_id', array('value' => $model->id)); ?>
        <?php echo $form->dropDownListGroup($product_has_child, 'child_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Product::model()->findAll(array('condition' => $product_condition)), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
        <?php echo $form->textFieldGroup($product_has_child, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right')))); ?>
    </div>
    <div class="form-group text-center">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => Yii::t('app', 'Add Child'),
        ));
        ?>

    </div>
</div>
<?php $this->endWidget(); ?>


<div class="col-md-9">


    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'product-children-grid',
        'dataProvider' => $product_has_children->search(),
        'summaryText' => false,

        'filter' => null,
        'columns' => array(

            array(
                'header' => Yii::t('app', 'Child'),

                'value' => '$data->child->title',
                'htmlOptions' => array('class' => 'col-md-2'),
            ),
            array(
                'header' => Yii::t('app', 'Description'),

                'value' => '$data->child->description'
            ),
            array(
                'header' => Yii::t('app', 'Quantity'),

                'value' => '$data->quantity',
                'htmlOptions' => array('class' => 'text-right col-md-1'),
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
                        'url' => 'Yii::app()->createUrl("systemSettings/product/ajaxRemoveChild/id/".$data->id)',

                        'options' => array(

                            'class' => 'btn btn-xs delete'
                        ),

                    )
                ),
            ),
        ),
    )); ?>

</div>

