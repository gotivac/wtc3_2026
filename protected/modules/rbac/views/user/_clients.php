<div class="col-md-2 well text-center">

    <?php $client_condition = empty($model->clients) ? '' : 'id NOT IN (' . implode(',', UserHasClient::model()->getClientIds($model->id)) . ')'; ?>

    <div class="form-group">
        <?php echo CHtml::dropDownList('client_id', '', CHtml::listData(Client::model()->findAll(array('condition' => $client_condition)), 'id', 'title'), array('class' => 'form-control selectpicker', 'empty' => '')); ?>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" onclick="addClient()"><?= Yii::t('app', 'Add Client'); ?></button>

    </div>
</div>
<div class="col-md-10">


    <?php $this->widget('booster.widgets.TbGridView', array(
        'id' => 'client-grid',
        'dataProvider' => $user_has_clients->search(),
        'summaryText' => false,
        'afterAjaxUpdate' => 'reinstallUserActions',
        'filter' => null,
        'columns' => array(

            array(
                'name' => 'client_id',
                'value' => '$data->client->title'
            ),

            array(
                'header' => 'View',
                'type' => 'raw',
                'value' => function ($data) {
                    $has_right = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $data->id, 'action' => 'view'));
                    if ($has_right == null) {
                        $checked = false;
                    } else {
                        $checked = true;
                    }

                    return CHtml::checkBox('UserHasClientAction[' . $data->id . '][view]', $checked, array('class' => 'user-action'));
                },
                'htmlOptions' => array('class' => 'col-md-1 text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),


            ),
            array(
                'header' => 'Create',
                'type' => 'raw',
                'value' => function ($data) {
                    $has_right = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $data->id, 'action' => 'create'));
                    // return var_dump($has_right);
                    if ($has_right == null) {
                        $checked = false;
                    } else {
                        $checked = true;
                    }

                    return CHtml::checkBox('UserHasClientAction[' . $data->id . '][create]', $checked, array('class' => 'user-action'));
                },
                'htmlOptions' => array('class' => 'col-md-1 text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),


            ),
            array(
                'header' => 'Update',
                'type' => 'raw',
                'value' => function ($data) {
                    $has_right = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $data->id, 'action' => 'update'));
                    // return var_dump($has_right);
                    if ($has_right == null) {
                        $checked = false;
                    } else {
                        $checked = true;
                    }

                    return CHtml::checkBox('UserHasClientAction[' . $data->id . '][update]', $checked, array('class' => 'user-action'));
                },
                'htmlOptions' => array('class' => 'col-md-1 text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),


            ),
            array(
                'header' => 'Delete',
                'type' => 'raw',
                'value' => function ($data) {
                    $has_right = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $data->id, 'action' => 'delete'));
                    // return var_dump($has_right);
                    if ($has_right == null) {
                        $checked = false;
                    } else {
                        $checked = true;
                    }

                    return CHtml::checkBox('UserHasClientAction[' . $data->id . '][delete]', $checked, array('class' => 'user-action'));
                },
                'htmlOptions' => array('class' => 'col-md-1 text-center'),
                'headerHtmlOptions' => array('class' => 'text-center'),


            ),

            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'template' => '{delete}',
                'class' => 'booster.widgets.TbButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'label' => Yii::t('app', 'Update'),
                        'options' => array(
                            'class' => 'btn btn-xs update'
                        )
                    ),
                    'delete' => array(
                        'label' => Yii::t('app', 'Delete'),
                        'url' => 'Yii::app()->createUrl("rbac/user/ajaxRemoveClient/id/".$data->id)',

                        'options' => array(

                            'class' => 'btn btn-xs delete'
                        ),

                    )
                ),
            ),
        ),
    )); ?>

</div>


<?php

/* ADD CLIENT MODAL */
$this->beginWidget('booster.widgets.TbModal', array(
    'id' => "addClient",
    'fade' => false,
    'options' => array('size' => 'large')
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 id="modal-title1"><?= Yii::t('app','Access rights');?></h4>
</div>
<div class="modal-body">
    <?php

    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'client-form',
        'type' => 'vertical',
        'enableAjaxValidation' => false,
    ));
    ?>
    <h2 id="client-title"></h2>
    <?= CHtml::hiddenField('UserHasClient[client_id]');?>
    <?= CHtml::hiddenField('UserHasClient[user_id]',$model->id);?>
<div class="form-group col-md-2">
<?= CHtml::checkbox('UserHasClientAction[view]',true);?> <label class="control-label">View</label>
</div>
<div class="form-group col-md-2">
<?= CHtml::checkbox('UserHasClientAction[create]',false);?> <label class="control-label">Create</label>
</div>
    <div class="form-group col-md-2">
<?= CHtml::checkbox('UserHasClientAction[update]',false);?> <label class="control-label">Update</label>
    </div>
    <div class="form-group col-md-2">
<?= CHtml::checkbox('UserHasClientAction[delete]',false);?> <label class="control-label">Delete</label>
    </div>
    <div class="clearfix"></div>
    <div class="form-actions">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div>

<?php $this->endWidget(); ?>

<?php
Yii::app()->clientScript->registerScript('userActions', "
	function reinstallUserActions(id, data) {
        $('.user-action').on('change', function () {
            let checkbox = $(this);
            let checked = $(this).is(':checked');
            let userHasClient = $(this).prop('id').split('_');
            let id = userHasClient[1];
            let action = userHasClient[2];
            $.ajax({
                url: '". Yii::app()->createUrl('rbac/user/ajaxClientRight')."',
                data: {'id': id, 'action': action, 'checked': checked},
                type: 'post',
                success: function (data) {
                    if (data == 'S') {
                        checkbox.parent().css('background-color', '#efe');
                    }
                    if (data == 'D') {
                        checkbox.parent().css('background-color', '#fee');
                    }
                }
            })
        });
	}"
);
?>
<script>
    $(document).ready(function () {
        $('.user-action').on('change', function () {
            let checkbox = $(this);
            let checked = $(this).is(':checked');
            let userHasClient = $(this).prop('id').split('_');
            let id = userHasClient[1];
            let action = userHasClient[2];
            $.ajax({
                url: '<?= Yii::app()->createUrl('rbac/user/ajaxClientRight');?>',
                data: {'id': id, 'action': action, 'checked': checked},
            type: 'post',
                success: function (data) {
                if (data == 'S') {
                    checkbox.parent().css('background-color', '#efe');
                }
                if (data == 'D') {
                    checkbox.parent().css('background-color', '#fee');
                }
            }
        })
        });
        $('#UserHasClientAction_action_view').on('click',function(){
           if (!$(this).is(':checked')) {
               $('#UserHasClientAction_action_create').removeProp('checked').prop('disabled','disabled');
               $('#UserHasClientAction_action_update').removeProp('checked').prop('disabled','disabled');
               $('#UserHasClientAction_action_delete').removeProp('checked').prop('disabled','disabled');
           } else {
               $('#UserHasClientAction_action_create').removeProp('disabled');
               $('#UserHasClientAction_action_update').removeProp('disabled');
               $('#UserHasClientAction_action_delete').removeProp('disabled');
           }
        });
    });

    function addClient()
    {
        let client_id = $('#client_id').val();
        let client_name = $('#client_id :selected').text();
        if (client_id && client_name) {

            $('#client-title').html(client_name);
            console.log(client_id);
            $('#UserHasClient_client_id').val(client_id);
            $('#addClient').modal('show');
        }
    }

</script>