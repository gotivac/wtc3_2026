<footer>
    
   <?php if (!Yii::app()->user->isGuest): ?>
                
                <div class="text-center row">

                <?php echo CHtml::link('<i class="glyphicon glyphicon-home"></i>',Yii::app()->createUrl('site/index'),array('class'=>'btn btn-success btn-xs'));?>
                <?php echo in_array('inbound',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-arrow-down"></i>',Yii::app()->createUrl('/inbound'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('outbound',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-arrow-up"></i>',Yii::app()->createUrl('/outbound'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('manipulate',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-refresh"></i>',Yii::app()->createUrl('/relocate'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('manipulate',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-indent-left"></i>',Yii::app()->createUrl('/split'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('web',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-import"></i>',Yii::app()->createUrl('/web/fill'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('web',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-export"></i>',Yii::app()->createUrl('/web/empty'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('web',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-circle-arrow-up"></i>',Yii::app()->createUrl('/web/start'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('control',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-pause"></i>',Yii::app()->createUrl('/orderControl/product'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo in_array('info',$this->user->rf_access) ? CHtml::link('<i class="glyphicon glyphicon-info-sign"></i>',Yii::app()->createUrl('/info'),array('class'=>'btn btn-default btn-xs')) : '';?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-off"></i>',Yii::app()->createUrl('site/logout'),array('class'=>'btn btn-danger btn-xs'));?>

                </div>
                <?php endif; ?>
</footer>

