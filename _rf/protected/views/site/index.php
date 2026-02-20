<h1 class="text-center">WTC3 RF</h1>
<hr>
<div class="row" style="margin-top:15px;">


    <div class="col-xs-6 text-center form-group">

        <?php if (in_array('inbound',$this->user->rf_access)): ?>
        <a href="<?php echo Yii::app()->createUrl('inbound');?>"><button class="btn btn-default"  style="width:100%"><i class="glyphicon glyphicon-arrow-down"></i> <?php echo Yii::t('app','Inbound');?></button></a>
        <?php endif; ?>
    </div>
    
    

    <div class="col-xs-6 text-center form-group">


        <?php if (in_array('outbound',$this->user->rf_access)): ?>
        <a href="<?php echo Yii::app()->createUrl('outbound');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-arrow-up"></i> <?php echo Yii::t('app','Outbound');?></button></a>
        <?php endif; ?>
    </div>
    
    
</div>
<?php if (in_array('manipulate',$this->user->rf_access)): ?>
<div class="row">
    <div class="col-xs-6 text-center form-group">



        <a href="<?php echo Yii::app()->createUrl('relocate');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-refresh"></i> <?php echo Yii::t('app','Relokacija');?></button></a>
    </div>


    <div class="col-xs-6 text-center form-group">



        <a href="<?php echo Yii::app()->createUrl('split');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-indent-left"></i> <?php echo Yii::t('app','Raspodela');?></button></a>
    </div>


</div>
<?php endif;?>
<?php if (in_array('web',$this->user->rf_access)): ?>
<div class="row">
    <div class="col-xs-3 text-center form-group">



        <a href="<?php echo Yii::app()->createUrl('web/fill');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-import"></i> <?php echo Yii::t('app','W');?></button></a>
    </div>
    <div class="col-xs-3 text-center form-group">



        <a href="<?php echo Yii::app()->createUrl('web/empty');?>"><button class="btn btn-default" style="width:100%"><?php echo Yii::t('app','W');?> <i class="glyphicon glyphicon-export"></i></button></a>
    </div>



    <div class="col-xs-6 text-center form-group">
        <a href="<?php echo Yii::app()->createUrl('web/start');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-circle-arrow-up"></i> <?php echo Yii::t('app','W Pikovanje');?></button></a>
    </div>


</div>
<?php endif;?>

<div class="row">
    <div class="col-xs-6 text-center form-group">


        <?php if (in_array('control',$this->user->rf_access)): ?>
        <a href="<?php echo Yii::app()->createUrl('orderControl/product');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-pause"></i> <?php echo Yii::t('app','Kontrola');?></button></a>
        <?php endif; ?>
    </div>



    <div class="col-xs-6 text-center form-group">
        <?php if (in_array('info',$this->user->rf_access)): ?>
        <a href="<?php echo Yii::app()->createUrl('/info');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-info-sign"></i> <?php echo Yii::t('app','Informacije');?></button></a>
        <?php endif; ?>
    </div>


</div>
<?php if (in_array('inventory',$this->user->rf_access)): ?>
<div class="row">
    <div class="col-xs-6 text-center form-group">



            <a href="<?php echo Yii::app()->createUrl('inventory');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-inbox"></i> <?php echo Yii::t('app','Inventory');?></button></a>

    </div>

    <div class="col-xs-6 text-center form-group">



        <a href="<?php echo Yii::app()->createUrl('inventory/slocChange');?>"><button class="btn btn-default" style="width:100%"><i class="glyphicon glyphicon-adjust"></i> <?php echo Yii::t('app','Zamena SLOC');?></button></a>

    </div>




</div>
<?php endif; ?>

