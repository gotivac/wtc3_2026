<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $client_id
 * @property integer $product_type_id
 * @property integer $load_carrier_id
 * @property integer $package_id
 * @property string $external_product_number
 * @property string $internal_product_number
 * @property string $product_barcode
 * @property string $title
 * @property string $description
 * @property string $width
 * @property string $length
 * @property string $height
 * @property string $weight
 * @property integer $pieces_in_package
 * @property integer $packages_on_pallet
 * @property integer $stock_minimum
 * @property integer $stock_maximum
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Client $client
 * @property LoadCarrier $loadCarrier
 * @property ProductType $productType
 */
class Product extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public $barcode_and_title;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('internal_product_number,product_barcode','unique'),
            array('client_id, title, internal_product_number, product_barcode', 'required'),
            array('client_id, product_type_id, load_carrier_id, package_id, pieces_in_package, packages_on_pallet, stock_minimum, stock_maximum, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('external_product_number, internal_product_number, product_barcode, title', 'length', 'max' => 255),
            array('width, length, height, weight', 'length', 'max' => 10),
            array('description, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, client_id, product_type_id, load_carrier_id, package_id, external_product_number, internal_product_number, product_barcode, title, description, width, length, height, weight, pieces_in_package, packages_on_pallet, stock_minimum, stock_maximum, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
            'loadCarrier' => array(self::BELONGS_TO, 'LoadCarrier', 'load_carrier_id'),
            'productType' => array(self::BELONGS_TO, 'ProductType', 'product_type_id'),
            'children' => array(self::HAS_MANY, 'ProductHasChild', 'product_id'),
            'packages' => array(self::MANY_MANY, 'Package', 'product_has_package(product_id,package_id)'),
            'productHasPackages' => array(self::HAS_MANY, 'ProductHasPackage', 'product_id'),
            'defaultPackage' => array(self::BELONGS_TO, 'Package', 'package_id'),

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client'),
            'product_type_id' => Yii::t('app', 'Product Type'),
            'load_carrier_id' => Yii::t('app', 'Load Carrier'),
            'package_id' => Yii::t('app', 'Default Package'),

            'external_product_number' => Yii::t('app', 'External Product Number'),
            'internal_product_number' => Yii::t('app', 'Internal Product Number'),
            'product_barcode' => Yii::t('app', 'Product Barcode'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'width' => Yii::t('app', 'Width'),
            'length' => Yii::t('app', 'Length'),
            'height' => Yii::t('app', 'Height'),
            'weight' => Yii::t('app', 'Weight'),
            'pieces_in_package' => Yii::t('app', 'Pieces In Package'),
            'packages_on_pallet' => Yii::t('app', 'Packages On Pallet'),
            'stock_minimum' => Yii::t('app', 'Stock Minimum'),
            'stock_maximum' => Yii::t('app', 'Stock Maximum'),
            'created_user_id' => Yii::t('app', 'Created User'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'updated_user_id' => Yii::t('app', 'Updated User'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('client_id', $this->client_id);
        $criteria->compare('product_type_id', $this->product_type_id);
        $criteria->compare('load_carrier_id', $this->load_carrier_id);
        $criteria->compare('package_id', $this->package_id);

        $criteria->compare('external_product_number', $this->external_product_number, true);
        $criteria->compare('internal_product_number', $this->internal_product_number, true);
        $criteria->compare('product_barcode', $this->product_barcode, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('width', $this->width, true);
        $criteria->compare('length', $this->length, true);
        $criteria->compare('height', $this->height, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('pieces_in_package', $this->pieces_in_package);
        $criteria->compare('packages_on_pallet', $this->packages_on_pallet);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : 1;
            $this->created_dt = date('Y-m-d H:i:s');
        } else {
            $this->updated_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : 1;
            $this->updated_dt = date('Y-m-d H:i:s');
        }

        $this->width = $this->width != '' ? str_replace(',', '.', $this->width) : null;
        $this->height = $this->height != '' ? str_replace(',', '.', $this->height) : null;
        $this->length = $this->length != '' ? str_replace(',', '.', $this->length) : null;
        $this->weight = $this->weight != '' ? str_replace(',', '.', $this->weight) : null;
        $this->internal_product_number = trim($this->internal_product_number);
        $this->external_product_number = trim($this->external_product_number);
        $this->product_barcode = trim($this->product_barcode);

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if (isset(Yii::app()->user)) {
            Yii::app()->Helpers->saveLog($this);
        }
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        if (isset(Yii::app()->user)) {
            $copy = $this;
            $copy->scenario = 'delete';
            Yii::app()->Helpers->saveLog($copy);
        }
        return parent::beforeDelete();
    }

    public function findAllIdsByClientId($client_id = false)
    {

        $sql = 'SELECT id FROM ' . $this->tableName();
        if ($client_id) {
            $sql .= ' WHERE client_id = ' . $client_id;
        }
        return Yii::app()->db->createCommand($sql)->queryColumn();

    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'product';
    }

    public function afterFind()
    {
        $this->width = round($this->width);
        $this->height = round($this->height);
        $this->length = round($this->length);
        $this->barcode_and_title = $this->product_barcode.' - ' . $this->title;
        return parent::afterFind();
    }


    public function calcPaletts($quantity)
    {

        if ($this->defaultPackage) {
            $pieces_per_palett = $this->defaultPackage->product_count * $this->defaultPackage->load_carrier_count;
        } else {
            $pieces_per_palett = 1;
        }

        if ($pieces_per_palett == 0) {
            $pieces_per_palett = 1;
        }

        return ceil($quantity / $pieces_per_palett);
    }

    public function setDefaultPackage($product_count, $load_carrier_count)
    {
        if ($this->defaultPackage && $this->defaultPackage->product_count == $product_count && $this->defaultPackage->load_carrier_count == $load_carrier_count) {
            return true;
        }
        foreach ($this->packages as $package) {
            if ($package->product_count == $product_count && $package->load_carrier_count == $load_carrier_count) {
                $this->package_id = $package->id;
                if ($this->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function createDefaultPackage(int $product_count, int $load_carrier_count)
    {
        $package = new Package;
        $package->attributes = array(
            'title' => ($this->client ? $this->client->title.'_' : 'UNKNOWN_') . '_' . $product_count. '_' . $load_carrier_count . '_',
            'product_count' => $product_count,
            'load_carrier_count' => $load_carrier_count,
        );
        if ($package->save()) {
            $this->package_id = $package->id;
            if ($this->save()) {
                $product_has_package = new ProductHasPackage;
                $product_has_package->attributes = array(
                    'product_id' => $this->id,
                    'package_id' => $package->id,
                );
                $product_has_package->save();
                return $package;
            }
        }

        return false;

    }

    public function getActivityPalettIds()
    {
        return Yii::app()->db->createCommand('SELECT activity_palett_id FROM activity_palett_has_product WHERE product_id = ' . $this->id . ' AND quantity > 0')->queryColumn();
    }

    public function getTotalQuantity($pickable = false)
    {
        $palett_quantity = ActivityPalettHasProduct::model()->getTotalQuantity($pickable,$this->id);
        $web_quantity = SlocHasProduct::model()->getTotalQuantity($this->id);
        return $palett_quantity + $web_quantity;
    }

    public function getStockQuantity($pickable=false){
        $palett_quantity = ActivityPalettHasProduct::model()->getTotalQuantity($pickable,$this->id);
        return $palett_quantity;
    }
}
