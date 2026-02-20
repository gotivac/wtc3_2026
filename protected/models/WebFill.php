<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class WebFill extends CFormModel {

    public $sscc_source;
    public $sloc_destination;
    public $product_id;
    public $product_barcode;
    public $quantity;
    public $packages;
    public $units;
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // email and password are required
            array('sscc_source, sloc_destination,product_barcode,quantity,packages,units', 'required'),

        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'sscc_source' => Yii::t('app', 'Sa palete (SSCC)'),
            'sloc_destination' => Yii::t('app', 'Na SLOC'),
            'product_barcode' => Yii::t('app', 'Barkod proizvoda'),
            'quantity' => Yii::t('app', 'Količina'),
            'packages' => Yii::t('app', 'Paketa'),
            'units' => Yii::t('app', 'Komada'),
        );
    }



}
