<h5>
    <div class="text-left col-xs-10">
        <?= $model->activityOrder->order_number; ?> &bull; <?= $model->activityOrder->client->title; ?>
        &bull; <?= $model->sloc_code; ?><br>
    </div>
    <div class="text-right col-xs-2">
        <?php if ($model->pick_type == 'move'): ?>
            <a class="btn btn-primary btn-xs" href="<?= Yii::app()->createUrl('/split/index'); ?>"><i
                        class="glyphicon glyphicon-arrow-left"></i></a>
        <?php else: ?>
            <a class="btn btn-primary btn-xs"
               href="<?= Yii::app()->createUrl('/outbound/pick' . ucfirst($model->pick_type) . '/' . $model->activity_order_id); ?>"><i
                        class="glyphicon glyphicon-arrow-left"></i></a>
        <?php endif; ?>
    </div>
</h5>
<div class="clearfix"></div>
<p></p>
<?php if ($model->pick_type != 'move'): ?>
    <p class="well">
        <?= Product::model()->findByPk($model->product_id)->title; ?>

        <span style="float:right" class="text-right">
            <a href="<?=Yii::app()->createUrl('/pick/alternate/' . $model->id);?>">
            <i class="glyphicon glyphicon-search"></i>
            </a>
        </span>

    </p>
<?php endif; ?>


<?php if ($model->pick_type == 'palett'): ?>
    <?php echo $this->renderPartial('_form_palett', array('model' => $model, 'order' => $order)); ?>
<?php elseif ($model->pick_type == 'product'): ?>
    <?php echo $this->renderPartial('_form_product', array('model' => $model, 'order' => $order)); ?>
<?php elseif ($model->pick_type == 'move'): ?>
    <?php echo $this->renderPartial('_form_split', array('model' => $model, 'order' => $order)); ?>
<?php endif; ?>

<?php if ($success == 1 && !$model->hasErrors()): ?>

    <script>
        $(document).ready(function () {
            $('#content').addClass("alert-success");
            setTimeout(() => {
                $('#content').removeClass("alert-success");
            }, 2000);
        });
    </script>
<?php endif; ?>
<?php if ($success == 0): ?>
    <script>
        $(document).ready(function () {
            $("#content").addClass("alert-danger");

        });
    </script>
<?php endif; ?>
