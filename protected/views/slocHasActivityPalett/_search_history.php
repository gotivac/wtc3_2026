<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
    'id'=>'search-form'
)); ?>

<div class="col-md-2">
    <?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('maxlength' => 255)))); ?>
</div>

<div class="col-md-1 text-right">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => 'Traži',
            'htmlOptions' => array('class' => 'col-md-12'),
        )); ?>
    </div>
</div>
<div class="col-md-1">
    <div class="form-group">
        <label class="control-label">&nbsp;</label><br>
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'default',
            'context' => 'success',
            'label' => 'Excel',
            'id' => 'excel',
            'htmlOptions' => array('name' => 'excel', 'class' => 'col-md-12'),

        )); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<div class="clearfix"></div>
<hr>
<script>
    $('#excel').on('click', function () {
        let data = $('#search-form').serialize();
        location.href = location.href + '?' + data + '&excel=true';
    });
</script>