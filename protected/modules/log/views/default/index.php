<?php

$this->breadcrumbs = array(
    Yii::t('app', 'Change Logs') => array('index'),
    Yii::t('app', 'List'),
);

?>


<?php

$model_names = Yii::app()->db->createCommand("SELECT DISTINCT model_name id,model_name title FROM change_log")->queryAll();

$a = '';
$this->widget('booster.widgets.TbGridView', array(
    'id' => 'change-log-grid',
    'dataProvider' => $model->search(),
    'summaryText' => Yii::t('app', 'Showing {start} - {end} of {count}'),
    //'pager' => array('class' => 'CLinkPager', 'header' => '', 'nextPageLabel' => Yii::t('app', "Next"), 'prevPageLabel' => Yii::t('app', 'Previous')),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'author_search',
            'value' => '$data->created_user_id != null ? User::model()->findByPk($data->created_user_id)->name : ""',
        ),
        array(
            'name' => 'created_dt',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'model_name',
            'type' => 'raw',
            'filter' => CHtml::listData($model_names, "id", "title"),
            'htmlOptions' => array('class' => 'text-center')
            
        ),
       
         array(
            'name' => 'model_id',
             'htmlOptions' => array('class' => 'text-right')
        ),
        
        array(
            'name' => 'scenario',
            'type' => 'raw',
            'filter' => CHtml::listData(array(array('id' => 'insert', 'title' => 'insert'), array('id' => 'update', 'title' => 'update'),array('id'=>'delete','title'=>'delete')), 'id', 'title'),
            'htmlOptions' => array('class' => 'text-center')
            
        ),
        array(
            'name' => 'data',
            'type' => 'raw',
            'value' => function($data) use ($a) {
                $arr = json_decode($data->data);

                $str = '';
                foreach ($arr as $k => $v) {
                    $str .= '<b>' . $k . '</b>: ' . $v . '<br>';
                }
                return $str.$a;
            }),
        
    ),
));
?>
