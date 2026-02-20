<h5 class="text-center">
    INBOUND AKTIVNOSTI
</h5>
<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model->inbound(),
    'id' => 'activity-list',
    'template' => '{pager}{items}{pager}',
    'itemView' => '_activity',


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
