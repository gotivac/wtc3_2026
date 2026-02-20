
<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'time-slot-grid',
    'dataProvider' => $time_slot_details->search(),
    'summaryText' => false,
    'pager' => null,
    'filter' => null,
   'afterAjaxUpdate' => 'function(){
    location.href = location.href.split("?")[0]+"?tab=1";
    }',


    'columns' => array(


        array(
            'name' => 'client_id',

            'value' => '$data->client ? $data->client->title : ""',
            'footer'=> Yii::t('app','TOTAL:'),
            'footerHtmlOptions' => array('style' => 'text-align:left;font-weight:bold'),
            'htmlOptions' => array('class'=>'col-md-4')
        ),
        array(
            'header' => Yii::t('app','Documents'),
            'type' => 'raw',
            'value' => function($data) {
                $result = '<ul>';
                foreach ($data->attachments as $attachment) {
                    $result .= '<li>'.CHtml::link($attachment->filename,Yii::app()->createUrl('/timeSlot/ajaxDownloadAttachment/'.$attachment->id));
                    $result .= '</li>';
                }
                $result .= '</ul>';
                return $result;
            }

        ),
        array(
            'name' => 'paletts',
            'htmlOptions' => array('class'=>'text-right col-md-1'),
            'footer'=> $model->totalPaletts,
            'footerHtmlOptions' => array('style' => 'text-align:right;font-weight:bold'),


        ),

        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'template' => '{delete}',
            'class' => 'booster.widgets.TbButtonColumn',
            'buttons' => array(

                'delete' => array(
                    'label' => Yii::t('app', 'Delete'),
                    'url' => 'Yii::app()->createUrl("timeSlot",array("ajaxDeleteDetail"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-xs delete',

                    ),

                )
            ),
        ),
    ),
)); ?>


