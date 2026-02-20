<h5 class="text-center">
    <?= $activity->gate->title . '  &bull;  ' . CHtml::link('Primljeno paleta: '. count($activity->scannedSSCCs),Yii::app()->createUrl("/activityPalettHasProduct/viewActivity/" . $activity->id));?></h5>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
    'id'=>'activity-palett-has-product-form',
    'type'=> 'horizontal',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

<div class="col-xs-9">
    <?php echo $form->textFieldGroup($model,'sscc',array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'),'widgetOptions'=>array('htmlOptions'=>array('maxlength'=>255)),'labelOptions'=>array('label'=>false))); ?>
    <p class="help-block">Skenirajte barkod palete</p>
</div>
<div class="col-xs-3 text-left">


        <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>

</div>

<?php $this->endWidget(); ?>
<div class="clearfix"></div>


<p>
<div class="col-xs-12 text-center">
    <a onclick="if (!confirm('PAŽNJA!\n\rDa li zaista želiš da poništiš prijem?')) return false;" href="<?=Yii::app()->createUrl('/inbound/deleteActivity/' . $activity->id);?>" class="btn btn-small btn-danger">Poništi prijem</a>
</div>
</p>
<p>&nbsp;</p>
<p>
<div class="col-xs-12 text-center">
    <a onclick="if (!confirm('Da li zaista želiš da završiš?')) return false;" href="<?=Yii::app()->createUrl('/inbound/closeActivity/' . $activity->id);?>" class="btn btn-small btn-success">Završi prijem</a>
</div>
</p>
<div class="clearfix"></div>

<script>
    $(document).ready(function () {
        $('#ActivityPalettHasProduct_sscc').focus().select();
    });
</script>