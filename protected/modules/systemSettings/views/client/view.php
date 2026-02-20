<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Clients') => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => Yii::t('app', 'List'), 'url' => array('index')),
    array('label' => Yii::t('app', 'Update'), 'url' => array('update', 'id' => $model->id)),
);
?>

<div class="alert-placeholder">

</div>
<div class="col-md-6 col-sm-12">
    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'title',
            'official_title',
            'tax_number',
            'domain',
            array(
                'name' => Yii::t('app', 'Location'),
                'value' => $model->location ? $model->location->title : '',
            ),
            array(
                'name' => Yii::t('app', 'Section'),
                'value' => $model->section ? $model->section->title : '',
            ),
            array(
                    'name' => 'unloading_level_id',
                    'value' => $model->unloadingLevel ? $model->unloadingLevel->title : "",
            ),
            array(
                'name' => Yii::t('app','Storage Types'),
                'type' => 'raw',
                'value' => function($model) {
                    $result = '<ul>';
                    foreach ($model->storageTypes as $storage_type) {

                        $result .= '<li>'.$storage_type->title. ($storage_type->pickup == 1 ? ' - pickup' : '') .  '</li>';
                    }
                    $result .= '</ul>';
                    return $result;
                }
            ),
            'client_identification',
            'postal_code',
            'city',
            'address',
            'country',
            'contact_person',
            'phone',
            'company_number',

            /*
            array(

                    'name' => Yii::t('app','Buyers'),
                    'type' => 'raw',
                    'value' => function($model) {
                        $list = '<ul>';
                        foreach ($model->buyers as $buyer) {
                            $list .= '<li>' . $buyer->buyer->title;
                        }
                        $list .= '</ul>';
                        return $list;
                    }
            ),
            array(

                'name' => Yii::t('app','Suppliers'),
                'type' => 'raw',
                'value' => function($model) {
                    $list = '<ul>';
                    foreach ($model->suppliers as $supplier) {
                        $list .= '<li>' . $supplier->supplier->title;
                    }
                    $list .= '</ul>';
                    return $list;
                }
            ),
    */
            'client_identification',
            'created_dt',

            'updated_dt',
        ),
    )); ?>
</div>
<div class="col-md-3 col-sm-6">
    <h5><?= Yii::t('app', 'Buyers'); ?></h5>
    <ul>
        <?php foreach ($model->buyers as $buyer): ?>
            <li><?= $buyer->buyer->title; ?></li>
        <?php endforeach; ?>
    </ul>

</div>
<div class="col-md-3 col-sm-6">
    <h5><?= Yii::t('app', 'Suppliers'); ?></h5>
    <ul>
        <?php foreach ($model->suppliers as $supplier): ?>
            <li><?= $supplier->supplier->title; ?></li>
        <?php endforeach; ?>
    </ul>

</div>