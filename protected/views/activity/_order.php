<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'activity-order-form',
    'type' => 'vertical',
    'enableAjaxValidation' => false,
)); ?>

<?php echo $form->textFieldGroup($activity_order, 'order_number', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>

<?php echo $form->dropDownListGroup($activity_order, 'client_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'widgetOptions' => array('data' => CHtml::listData(Client::model()->findAllByAttributes(array('location_id' => $model->location_id)), 'id', 'title'), 'htmlOptions' => array('empty' => '', 'class' => 'selectpicker')))); ?>
<?php echo $form->dropDownListGroup($activity_order, 'customer_supplier_id', array('wrapperHtmlOptions' => array('class' => ' col-md-6 col-sm-12'), 'labelOptions' => array('label' => $model->direction == 'in' ? Yii::t('app', 'Supplier') : Yii::t('app', 'Buyer')), 'widgetOptions' => array('data' => CHtml::listData(Client::model()->findAllByAttributes(array('location_id' => $model->location_id)), 'id', 'title'), 'htmlOptions' => array('class' => 'selectpicker','empty' => $model->direction == 'in' ? Yii::t('app', 'Supplier') : Yii::t('app', 'Buyer'))))); ?>


    <div class="form-actions">
        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('app', 'Save'), '', array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {

                        $(".error").parent().removeClass("has-error");
                     
                        
                        if (typeof data.id != "undefined") {
                            $.each(data, function(key,val) {
                                $("#OrderClient_"+key+"_em_").remove();
                                $("#OrderClient_"+key).parent().removeClass("has-error");
                            });
                            location.href=location.protocol + "//" + location.host + location.pathname+"?tab=1";
                        }  else {
                            $.each(data, function(key,val) {
                            if (!$("#"+key+"_em_").is(":visible")) {
                                $("#"+key).after("<div id=\""+key+"_em_"+"\" class=\"help-block error\">"+val+"</div>");
                                $("#"+key).parent().addClass("has-error");
                                }
                            }); 
                        } 
                    }',
        ), array('class' => 'btn btn-primary'));
        ?>
    </div>
<?php $this->endWidget(); ?>