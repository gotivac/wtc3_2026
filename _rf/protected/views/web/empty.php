<h5>
    <div class="text-center col-xs-12">
        WEB POVRAT
    </div>

</h5>
<div class="clearfix"></div>

<?php
$this->widget('booster.widgets.TbAlert', array(
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array(),
    'userComponentId' => 'user',
    'alerts' => array( // configurations per alert type
        // success, info, warning, error or danger
        'success' => array('closeText' => '&times;'),
        'info', // you don't need to specify full config
        'warning' => array('closeText' => false),
        'error' => array('closeText' => Yii::t('app','Error')),
    ),
));
?>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'pick-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>




<?php echo $form->textFieldGroup($model, 'sloc_source', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

<?php echo $form->textFieldGroup($model, 'sscc_destination', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

<?php echo $form->textFieldGroup($model, 'product_barcode', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'skip')), 'labelOptions' => array('label' => false))); ?>

<?php echo $form->numberFieldGroup($model, 'quantity', array('wrapperHtmlOptions' => array('class' => 'col-md-6 col-sm-12'), 'widgetOptions' => array('htmlOptions' => array('class' => 'text-right skip', 'onfocus' => '$(this).select();')), 'labelOptions' => array('label' => false))); ?>


<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-small"><i class="glyphicon glyphicon-ok"></i></button>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#WebEmpty_sloc_source').focus().select();

        $(".skip").keypress(function (event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();

                var thisIndex = $(this).index('input:text');

                var next = thisIndex + 1;

                $('input:text').eq(next).focus();


            }
        });

    });



</script>