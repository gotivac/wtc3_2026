<?php

$this->breadcrumbs = array(
    Yii::t('app', 'Users') => array('index'),
    $model->name => array('update', 'id' => $model->id),
    Yii::t('app', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id), 'active' => true),
);
?>
    <div class="alert-placeholder">
        <?php

        $this->widget('booster.widgets.TbAlert', array(
            'fade' => true,
            'closeText' => '&times;', // false equals no close link
            'events' => array(),
            'htmlOptions' => array(),
            'userComponentId' => 'user',
            'alerts' => array(// configurations per alert type
                // success, info, warning, error or danger
                'success' => array('closeText' => '&times;'),
                'info', // you don't need to specify full config
                'warning' => array('closeText' => false),
                'error' => array('closeText' => Yii::t('app', 'Error')),
            ),
        ));
        ?>
    </div>

<?php

$this->widget(
    'booster.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'tabs' => array(
            array(
                'label' => Yii::t('app', 'Update'),
                'content' => $this->renderPartial('_form', array('model' => $model), true),
                'active' => ((!isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab'] == 0)) ? true : false,
                // 'url' => '?tab=0',

            ),

            array(
                'label' => Yii::t('app', 'Clients'),
                'content' => $this->renderPartial('_clients', array('model' => $model,'user_has_clients' => $user_has_clients), true),
                'active' => (isset($_GET['tab']) && $_GET['tab'] == 1) ? true : false,
                'visible' => $model->global_client == 0

            ),





        ),
    )
);

?>
