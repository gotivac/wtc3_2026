<?php

/**
 * This is the model class for table "sloc_has_product".
 *
 * The followings are the available columns in table 'sloc_has_product':
 * @property integer $id
 * @property integer $sloc_id
 * @property string $sloc_code
 * @property integer $product_id
 * @property string $product_barcode
 * @property integer $quantity
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Sloc $sloc
 */
class SlocHasProduct extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sloc_has_product';
    }

    public $product_search;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sloc_id, sloc_code, product_id, product_barcode, quantity', 'required'),
            array('sloc_id, product_id, quantity, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('sloc_code, product_barcode', 'length', 'max' => 255),
            array('created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sloc_id, sloc_code, product_id, product_barcode, product_search, quantity, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'sloc' => array(self::BELONGS_TO, 'Sloc', 'sloc_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'sloc_id' => Yii::t('app', 'Sloc'),
            'sloc_code' => Yii::t('app', 'Sloc Code'),
            'product_id' => Yii::t('app', 'Product'),
            'product_search' => Yii::t('app', 'Product'),
            'product_barcode' => Yii::t('app', 'Product Barcode'),
            'quantity' => Yii::t('app', 'Quantity'),
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
        $criteria->compare('sloc_id', $this->sloc_id);
        $criteria->compare('sloc_code', $this->sloc_code, true);
        $criteria->compare('product_id', $this->product_id);

        $criteria->compare('t.product_barcode', $this->product_barcode, true);
        $criteria->compare('quantity', $this->quantity);

        $criteria->with = array('product');

        if ($this->product_search != '') {
            $criteria->compare('product.internal_product_number', $this->product_search, true);
            // $criteria->compare('product.title',$this->product_search,true);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SlocHasProduct the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');
        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        Yii::app()->Helpers->saveLog($this);
        return parent::beforeSave();
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function getRealQuantity($includeReserved = false)
        /** if includeReserved is true, reserved items counts in picked */
    {
        $sql = 'SELECT SUM(quantity) FROM sloc_has_product_log WHERE sloc_has_product_id = ' . $this->id;
        $logged = Yii::app()->db->createCommand($sql)->queryScalar();

        $sql = 'SELECT SUM(quantity) FROM pick_web WHERE ';
        if (!$includeReserved) {
            $sql .= 'status = 1 AND ';
        }
        $sql .= 'sloc_id = ' . $this->sloc_id . ' AND product_id = ' . $this->product_id;
        $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();

        $sql = 'SELECT SUM(quantity) FROM pick WHERE ';
        if (!$includeReserved) {
            $sql .= 'quantity > 0 AND ';
        }
        $sql .= 'sloc_id = ' . $this->sloc_id . ' AND product_id = ' . $this->product_id;
        $picked = Yii::app()->db->createCommand($sql)->queryScalar();


        $quantity = $this->quantity;


        if ($logged != NULL) {
            $quantity += $logged;
        }
        if ($web_picked != NULL) {
            $quantity -= $web_picked;
        }

        if ($picked != NULL) {
            $quantity -= $picked;
        }

        return $quantity;
    }

    public function getTotalQuantity($product_id)
    {

        $slocs = $this->findAllByAttributes(array('product_id' => $product_id));
        $total_quantity = 0;
        foreach ($slocs as $sloc) {
            $total_quantity += $sloc->getRealQuantity();
        }
        /** Stari nacin

                $sql = 'SELECT SUM(quantity) FROM ' . $this->tableName() . ' WHERE product_id=' . $product_id;;
                $total_quantity = Yii::app()->db->createCommand($sql)->queryScalar();


                $sql = 'SELECT SUM(quantity) FROM sloc_has_product_log WHERE product_id = ' . $product_id;
                $logged = Yii::app()->db->createCommand($sql)->queryScalar();

                $sql = 'SELECT SUM(quantity) FROM pick_web WHERE status = 1 AND sloc_code IS NOT NULL AND product_id = ' . $product_id;
                $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();

                $sql = 'SELECT SUM(quantity) FROM pick WHERE quantity > 0 AND  sscc_source IS NULL AND product_id = ' . $product_id;
                $picked = Yii::app()->db->createCommand($sql)->queryScalar();


                if ($logged != NULL) {
                    $total_quantity += $logged;
                }
                if ($web_picked != NULL) {
                    $total_quantity -= $web_picked;
                }

                if ($picked != NULL) {
                    $total_quantity -= $picked;
                }

        */
        return $total_quantity;

    }
}
