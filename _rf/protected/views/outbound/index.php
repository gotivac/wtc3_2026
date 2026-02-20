<h5>
    <div class="text-center col-xs-12">
        OUTBOUND AKTIVNOSTI
    </div>

</h5>
<div class="clearfix"></div>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'activity-palett-has-product-form',
    'type'=> 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

<div class="col-xs-9">
    <?php echo CHtml::dropDownList('section_id','',CHtml::listData($sections,'id','title'),array('class'=>'form-control','empty'=>'')); ?>
    <p class="help-block">Izaberi sekciju ili ostavi polje prazno kako bi video naloge iz svih sekcija.</p>
</div>
<div class="col-xs-3 text-left">


    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

</div>

<?php $this->endWidget(); ?>
