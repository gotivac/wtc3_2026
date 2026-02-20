<?php

/**
 * This is the model class for table "activity_order_product".
 *
 * The followings are the available columns in table 'activity_order_product':
 * @property integer $id
 * @property integer $activity_id
 * @property integer $activity_order_id
 * @property integer $order_product_id
 * @property integer $product_id
 * @property integer $package_id
 * @property integer $products_in_package
 * @property integer $products_on_palett
 * @property integer $packages_on_palett
 * @property integer $quantity
 * @property integer $paletts
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Activity $activity
 * @property ActivityOrder $activityOrder
 * @property Product $product
 * @property ActivityPalett[] $activityPaletts
 */
class ActivityOrderProduct extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivityOrderProduct the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activity_order_product';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id, activity_order_id,  product_id, quantity, paletts', 'required'),
            array('activity_id, activity_order_id, product_id, order_product_id, package_id, products_in_package, products_on_palett, packages_on_palett, quantity, paletts, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('created_dt, updated_dt', 'safe'),
            array('paletts', 'numerical', 'min' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity_id, activity_order_id, product_id, order_product_id, package_id, products_in_package, products_on_palett, packages_on_palett, quantity, paletts, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
            'activityOrder' => array(self::BELONGS_TO, 'ActivityOrder', 'activity_order_id'),
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'package' => array(self::BELONGS_TO, 'Package', 'package_id'),
            'activityPaletts' => array(self::HAS_MANY, 'ActivityPalett', 'activity_order_product_id'),

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity'),
            'activity_order_id' => Yii::t('app', 'Activity Order'),
            'product_id' => Yii::t('app', 'Product'),
            'package_id' => Yii::t('app', 'Package'),
            'products_in_package' => Yii::t('app', 'Products In Package'),
            'products_on_palett' => Yii::t('app', 'Products On Palett'),
            'packages_on_palett' => Yii::t('app', 'Packages On Palett'),
            'quantity' => Yii::t('app', 'Quantity'),
            'paletts' => Yii::t('app', 'Paletts'),
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
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('activity_order_id', $this->activity_order_id);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('package_id', $this->package_id);
        $criteria->compare('products_in_package', $this->products_in_package);
        $criteria->compare('products_on_palett', $this->products_on_palett);
        $criteria->compare('packages_on_palett', $this->packages_on_palett);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('paletts', $this->paletts);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            )
        ));
    }

    public function beforeValidate()
    {
        if ($this->paletts == 0) {
            $this->paletts = 1;
        }
        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        if ($this->activity->direction == 'out') {
            $product = Product::model()->findByPk($this->product_id);
            if ($product === null) {
                throw new ChttpException('404', 'Product not found.');
            }
            $quantity = $product->getTotalQuantity(true);
            if ($this->quantity > $quantity) {
                $this->addError('quantity', Yii::t('app', 'Not enough products. Maximum allowed for picking: ') . $product->title . ': ' . $quantity);
                return false;
            }
        }
        return parent::afterValidate();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');

            $product = Product::model()->findByPk($this->product_id);
            if ($product->defaultPackage) {
                $this->package_id = $product->defaultPackage->id;
                $this->products_in_package = $product->defaultPackage->product_count;
                $this->packages_on_palett = $product->defaultPackage->load_carrier_count;

            } else {
                $product->createDefaultPackage(1, 1);
            }

        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }

        $this->products_on_palett = $this->products_in_package * $this->packages_on_palett;

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->isNewRecord && $this->activity->direction == 'in') {
            $this->generatePaletts();
        }
        Yii::app()->Helpers->saveLog($this);

        return parent::afterSave();
    }

    public function generatePaletts()
    {

        $total_products = $this->quantity;
        for ($i = 1; $i <= $this->paletts; $i++) {
            $activity_palett = new ActivityPalett;
            if ($total_products >= $this->products_on_palett) {
                $quantity = $this->products_on_palett;
                $packages = $this->packages_on_palett;
            } else {
                $quantity = $total_products;
                $packages = ceil($quantity / $this->packages_on_palett);
            }
            $activity_palett->attributes = array(
                'activity_id' => $this->activityOrder->activity->id,
                'activity_order_id' => $this->activityOrder->id,
                'activity_order_product_id' => $this->id,
                'sscc' => ActivityPalett::newSSCC(),

            );

            if (!$activity_palett->save()) {
                throw new CHttpException('500', strip_tags(Chtml::errorSummary($activity_palett)));

            }
            $total_products -= $quantity;
        }
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function getControlledQuantity()
    {
        $sql = 'SELECT SUM(quantity) FROM activity_order_control WHERE product_id='.$this->product_id.' AND activity_order_id='.$this->activity_order_id;
        return Yii::app()->db->createCommand($sql)->queryScalar();
    }

}
