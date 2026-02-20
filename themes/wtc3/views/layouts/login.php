<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
    <?php if (isset($this->breadcrumbs) && isset(Yii::app()->session['authenticated'])): ?>
        <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
            'homeLink' => CHtml::link(Yii::t('app','Dashboard'),Yii::app()->createUrl('/')),
            'htmlOptions' => array('class' => 'breadcrumb')
        ));
        ?><!-- breadcrumbs -->
    <?php endif ?>


    <?php
    $this->widget('booster.widgets.TbMenu', array(
        /* 'type'=>'list', */
        //'encodeLabel'=>false,
        'type' => 'navbar',
        'items' => $this->menu,
    ));
    ?>
       
<?php echo $content; ?>

</div><!-- content -->
<?php $this->endContent(); ?>