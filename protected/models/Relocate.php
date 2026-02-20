<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Relocate extends CFormModel {

    public $sloc_source;
    public $sloc_destination;
    public $sscc;
    public $storage_type_id;
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // email and password are required
            array('sloc_destination, sscc,storage_type_id', 'required'),

        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'sloc_source' => Yii::t('app', 'Sa lokacije'),
            'sloc_destination' => Yii::t('app', 'Na lokaciju'),
            'sscc' => Yii::t('app', 'SSCC'),
            'storage_type_id' => Yii::t('app', 'Storage Type'),
        );
    }



}
