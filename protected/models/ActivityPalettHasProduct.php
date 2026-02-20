<?php

/**
 * This is the model class for table "activity_palett_has_product".
 *
 * The followings are the available columns in table 'activity_palett_has_product':
 * @property integer $id
 * @property integer $activity_palett_id
 * @property string $sscc
 * @property integer $product_id
 * @property string $product_barcode
 * @property string|null $delivery_number
 * @property string|null $volume
 * @property integer $quantity
 * @property integer $packages
 * @property integer $units
 * @property string $expire_date
 * @property string $batch
 * @property integer $pick_id
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ActivityPalett $activityPalett
 * @property Product $product
 */
class ActivityPalettHasProduct extends CActiveRecord
{
    public $activity = false;

    public $reason; // virtual variable for updating quantity

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_palett_id', 'required'),

            array('product_id, quantity', 'required', 'on' => 'update'),
            array('activity_palett_id, product_id, quantity, packages, units, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('sscc, product_barcode, batch', 'length', 'max' => 255),
            array('activity, reason, expire_date, created_dt, updated_dt, pick_id, delivery_number,volume', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity, activity_palett_id, sscc, product_id, product_barcode, delivery_number, volume, quantity, packages, units, expire_date, batch, pick_id, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'activityPalett' => array(self::BELONGS_TO, 'ActivityPalett', 'activity_palett_id'),
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'activity_palett_id' => Yii::t('app', 'Activity Palett'),
            'sscc' => 'SSCC',
            'product_id' => Yii::t('app', 'Product'),
            'product_barcode' => Yii::t('app', 'Product'),
            'delivery_number' => Yii::t('app', 'Delivery Number'),
            'volume' => Yii::t('app', 'Volume'),
            'quantity' => Yii::t('app', 'Quantity'),
            'reason' => Yii::t('app', 'Reason'),
            'packages' => Yii::t('app', 'Paketa'),
            'units' => Yii::t('app', 'Van paketa'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'batch' => Yii::t('app', 'Batch'),
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


        if ($this->activity !== false) {
            $activity_palett_ids = array();
            foreach ($this->activity->activityPaletts as $palett) {
                $activity_palett_ids[] = $palett->id;
            }
            if (!empty($activity_palett_ids)) {
                $criteria->addInCondition('activity_palett_id', $activity_palett_ids);
            } else {
                $criteria->compare('activity_palett_id', 0);
            }
        } else {
            $criteria->compare('activity_palett_id', $this->activity_palett_id);
        }

        $criteria->compare('id', $this->id);

        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('product_barcode', $this->product_barcode, true);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('packages', $this->packages);
        $criteria->compare('units', $this->units);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('batch', $this->batch, true);
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

    public function searchPresent()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

/*
        $sql = 'SELECT DISTINCT sscc_destination FROM pick WHERE status=1';
        $ssccs = Yii::app()->db->createCommand($sql)->queryColumn();
        $sql = 'SELECT id FROM activity_palett WHERE sscc IN ("' . implode('","',$ssccs) . '")';
        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

*/
        $sql = 'SELECT id FROM activity_palett WHERE direction="out"';
        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();
        $criteria->addNotInCondition('activity_palett_id', $activity_palett_ids);


        $criteria->compare('id', $this->id);

        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('product_barcode', $this->product_barcode, true);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('packages', $this->packages);
        $criteria->compare('units', $this->units);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('batch', $this->batch, true);
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

    public function beforeDelete()
    {


        if (SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $this->sscc))) {
            return false;
        }

        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }


    public function afterDelete()
    {
        $exists = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id' => $this->activity_palett_id));
        if (!$exists) {
            $accepted = Accept::model()->findByAttributes(array('activity_palett_id' => $this->activity_palett_id));
            if ($accepted) {
                $accepted->delete();
            }
        }
        return parent::afterDelete();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivityPalettHasProduct the static model class
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

            if ($this->packages == NULL) {
                $this->packages = 0;
            }

            /** SPRECAVANJE GRESKE KOJA NASTAJE USLED PROBLEMA SA KONEKCIJOM? I DVOSTRUKOG SMESTAJA NA PALETU */

            $duplicate = $this->findByAttributes(array('activity_palett_id' => $this->activity_palett_id, 'product_id' => $this->product_id, 'quantity' => $this->quantity, 'pick_id' => $this->pick_id));
            if ($duplicate) {

               return false;
            }


        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        $this->expire_date = ($this->expire_date != '..' || $this->expire_date != '') ? date('Y-m-d', strtotime($this->expire_date)) : null;

        $this->product_barcode = trim($this->product_barcode);
        $this->sscc = trim($this->sscc);

        if ($this->product_barcode != '' && Product::model()->findByAttributes(array('product_barcode' => $this->product_barcode)) === null) {
            $this->addError('product_barcode', 'Proizvod ne postoji.');
            return false;
        }
        if (ActivityPalett::model()->findByAttributes(array('sscc' => $this->sscc)) === null) {
            $this->addError('sscc', 'Paleta ne postoji.');
            return false;
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->expire_date = $this->expire_date != null ? date('d.m.Y', strtotime($this->expire_date)) : null;

        /*
                if ($this->packages != 0) {
                    $pieces_per_package = floor($this->quantity / $this->packages);
                    if ($this->product->defaultPackage) {
                        if ($this->product->defaultPackage->product_count == $pieces_per_package) {
                            return parent::afterSave();
                        }
                    }

                    foreach ($this->product->packages as $package) {
                        if ($package->product_count == $pieces_per_package) {
                            $this->product->package_id = $package->id;
                            $this->product->save();
                            return parent::afterSave();
                        }
                    }
               //     $this->product->createDefaultPackage($pieces_per_package, $this->packages);
                }
        */

        Yii::app()->Helpers->saveLog($this);

        return parent::afterSave();
    }


    public function afterFind()
    {
        $this->expire_date = $this->expire_date != null ? date('d.m.Y', strtotime($this->expire_date)) : null;

      
        return parent::afterFind();
    }

    public function getTotalQuantity($pickable = false, $product_id = false)
    {



        $activity_paletts_has_product = ActivityPalettHasProduct::model()->findAllByAttributes(array('product_id' => $product_id));

        $total_quantity = 0;

        foreach ($activity_paletts_has_product as $activity_palett_has_product) {
            if ($activity_palett_has_product->activityPalett->isLocated()) {
                if ($pickable) {
                    $total_quantity += $activity_palett_has_product->stockQuantity;
                } else {
                    $total_quantity += $activity_palett_has_product->realQuantity;

                }
            }
        }



        return $total_quantity;


    }

    public function getRealQuantity()
    {
        if ($this->sscc && $this->product_id && $this->activityPalett) {
            $sql = 'SELECT SUM(quantity) FROM pick WHERE sscc_destination IS NOT NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
            $picked = Yii::app()->db->createCommand($sql)->queryScalar();
            $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product_log WHERE activity_palett_has_product_id = ' . $this->id;
            $logged = Yii::app()->db->createCommand($sql)->queryScalar();
            $sql = 'SELECT SUM(quantity) FROM pick_web WHERE status = 1 AND activity_palett_id = ' . $this->activityPalett->id . ' AND product_id = ' . $this->product_id;
            $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();

            $quantity = $this->quantity;

            if ($picked != NULL) {
                $quantity -= $picked;
            }
            if ($logged != NULL) {
                $quantity += $logged;
            }
            if ($web_picked != NULL) {
                $quantity -= $web_picked;
            }

            return $quantity;
        }
        return 0;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activity_palett_has_product';
    }


    /**
     * @return int
     *
     * Return quantity minus reserved amounts
     */

    public function getStockQuantity()
    {
        $sql = 'SELECT SUM(quantity) FROM pick WHERE sscc_destination IS NOT NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
        $picked = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(target) FROM pick WHERE sscc_destination IS NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
        $reserved = Yii::app()->db->createCommand($sql)->queryScalar();

        $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product_log WHERE activity_palett_has_product_id = ' . $this->id;
        $logged = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(quantity) FROM pick_web WHERE status = 1 AND activity_palett_id = ' . $this->activityPalett->id . ' AND product_id = ' . $this->product_id;
        $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();

        $quantity = $this->quantity;

        if ($picked != NULL) {
            $quantity -= $picked;
        }

        if ($reserved != NULL) {
            $quantity -= $reserved;
        }
        if ($logged != NULL) {
            $quantity += $logged;
        }
        if ($web_picked != NULL) {
            $quantity -= $web_picked;
        }
        return $quantity;
    }

    /****************************************
     * @return array
     *
     * Return physical content
     */

    public function getContent()
    {
        $sql = 'SELECT SUM(quantity) FROM pick WHERE sscc_destination IS NOT NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
        $picked = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(quantity) FROM activity_palett_has_product_log WHERE activity_palett_has_product_id = ' . $this->id;
        $logged = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(quantity) FROM pick_web WHERE status = 1 AND activity_palett_id = ' . $this->activityPalett->id . ' AND product_id = ' . $this->product_id;
        $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();

        $quantity = $this->quantity;

        if ($picked != NULL) {
            $quantity -= $picked;
        }
        if ($logged != NULL) {
            $quantity += $logged;
        }
        if ($web_picked != NULL) {
            $quantity -= $web_picked;
        }

        $sql = 'SELECT SUM(units) FROM pick WHERE sscc_destination IS NOT NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
        $picked = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(units) FROM activity_palett_has_product_log WHERE activity_palett_has_product_id = ' . $this->id;
        $logged = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(quantity) FROM pick_web WHERE activity_palett_id IS NOT NULL AND sscc_source = "' . $this->sscc . '" AND product_id = ' . $this->product_id;
        $web_picked = Yii::app()->db->createCommand($sql)->queryScalar();
        $units = $this->units;

        if ($picked != NULL) {
            $units -= $picked;
        }
        if ($logged != NULL) {
            $units += $logged;
        }
        if ($web_picked != NULL) {
            $units -= $web_picked;
        }

        $sql = 'SELECT SUM(packages) FROM pick WHERE sscc_destination IS NOT NULL AND activity_palett_id = ' . $this->activity_palett_id . ' AND product_id = ' . $this->product_id;
        $picked = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = 'SELECT SUM(packages) FROM activity_palett_has_product_log WHERE activity_palett_has_product_id = ' . $this->id;
        $logged = Yii::app()->db->createCommand($sql)->queryScalar();

        $packages = $this->packages;
        if ($logged != NULL) {
            $packages += $logged;
        }

        if ($picked != NULL) {
            if ($units < 0 && $this->product && $this->product->defaultPackage) {
                $picked++;
                $units += $this->product->defaultPackage->product_count;

            }
            $packages -= $picked;
        }


        return array(
            'quantity' => $quantity,
            'packages' => $packages,
            'units' => $units,
        );


    }

    public function getHistory()
    {
        $picks = Pick::model()->findAllByAttributes(array('sscc_source'));
    }


}
