
<h5 class="text-center">
    PRERASPODELA
</h5>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model,
    'id' => 'order-list',
    'template' => '{items}{pager}',
    'itemView' => '_pick',


    'pager' => array('class' => 'CLinkPager',
        'header' => '',
        'nextPageLabel' => Yii::t('app', ">"),
        'prevPageLabel' => Yii::t('app', '<'),
        'lastPageLabel' => false,
        'firstPageLabel' => false,
        'htmlOptions' => array(
            'class' => 'pagination',
        )),
));