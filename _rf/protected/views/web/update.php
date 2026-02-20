<h5>
    <div class="text-center col-xs-10">
        <?= $model->webOrder->order_number;?> &bull;  <?=$sloc_code;?><br> <?= $sscc_source;?>
    </div>
    <div class="text-right col-xs-2">

            <a class="btn btn-primary btn-xs" href="<?=Yii::app()->createUrl('/web/'.$model->web_order_id);?>"><i class="glyphicon glyphicon-arrow-left"></i></a>

    </div>
</h5>
<div class="clearfix"></div>

<?php if ($model->activity_palett_id == null): ?>
    <?php echo $this->renderPartial('_form_w', array('model' => $model)); ?>
<?php else: ?>
    <?php echo $this->renderPartial('_form_s', array('model' => $model)); ?>
<?php endif; ?>

<?php if ($success == 1 && !$model->hasErrors()): ?>

    <script>
        $(document).ready(function(){
            $('#content').addClass("alert-success");
            setTimeout(() => {$('#content').removeClass("alert-success");}, 2000);
        });
    </script>
<?php endif; ?>
<?php if ($success == 0): ?>
    <script>
        $(document).ready(function(){
            $("#content").addClass("alert-danger");

        });
    </script>
<?php endif; ?>
