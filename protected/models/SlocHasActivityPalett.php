<?php

/**
 * This is the model class for table "sloc_has_activity_palett".
 *
 * The followings are the available columns in table 'sloc_has_activity_palett':
 * @property integer $id
 * @property string $sscc
 * @property string $sloc_code
 * @property integer $sloc_id
 * @property integer $activity_palett_id
 * @property integer $storage_type_id
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ActivityPalett $activityPalett
 * @property Sloc $sloc
 * @property StorageType $storageType
 */
class SlocHasActivityPalett extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sloc_has_activity_palett';
    }

    public $activity_palett_ids = false;

    public $order_number_search = false;

    public $isExcel = false;


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sloc_id, activity_palett_id, storage_type_id', 'required'),
            array('sloc_id, activity_palett_id, storage_type_id, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('sscc, sloc_code', 'length', 'max' => 255),
            array('created_dt, updated_dt, order_number_search', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sscc, sloc_code, isExcel, sloc_id, activity_palett_id, activity_palett_ids, storage_type_id, order_number_search, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'sloc' => array(self::BELONGS_TO, 'Sloc', 'sloc_id'),
            'storageType' => array(self::BELONGS_TO, 'StorageType', 'storage_type_id'),

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'sscc' => Yii::t('app', 'SSCC'),
            'sloc_code' => Yii::t('app', 'Sloc Code'),
            'sloc_id' => Yii::t('app', 'Sloc'),
            'activity_palett_id' => Yii::t('app', 'Activity Palett'),
            'order_number_search' => Yii::t('app', 'Order Number'),
            'storage_type_id' => Yii::t('app', 'Storage Type'),
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

        if ($this->activity_palett_ids !== false) {

            if (empty($this->activity_palett_ids)) {
                $criteria->compare('activity_palett_id', 0);
            } else {
                $criteria->addInCondition('activity_palett_id', $this->activity_palett_ids);
            }
        } else {
            $criteria->compare('activity_palett_id', $this->activity_palett_id);
        }


        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($user->location_id != null) {
            $criteria->compare('sloc.location_id', $user->location_id);
        }


        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.sscc', $this->sscc, true);
        $criteria->compare('t.sloc_code', $this->sloc_code, true);
        $criteria->compare('t.sloc_id', $this->sloc_id);

        $criteria->compare('storage_type_id', $this->storage_type_id);

        $criteria->compare('DATE_FORMAT(activityPalett.created_dt,"%d.%m.%Y")', $this->created_dt, true);
        $criteria->with = array('activityPalett', 'sloc');


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => $this->isExcel ? $this->isExcel : 100),
            'sort' => array(
                'defaultOrder' => 't.sloc_code ASC'
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SlocHasActivityPalett the static model class
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


            $sloc = Sloc::model()->findByPk($this->sloc_id);

            if ($sloc->reservedProduct) {

                $allowed = true;
                $activity_palett_has_products = ActivityPalettHasProduct::model()->findAllByAttributes(array('activity_palett_id' => $this->activity_palett_id));
                foreach ($activity_palett_has_products as $activity_palett_has_product) {
                    if ($activity_palett_has_product->product_id != $sloc->reservedProduct->id) {
                        $product = Product::model()->findByPk($activity_palett_has_product->product_id);
                        $allowed = false;
                    }
                }

                if (!$allowed) {
                    $this->addError('sloc_barcode', 'Proizvod ' .$product->product_barcode . ' - '. $product->title . ' nije dozvoljen u ' . $this->sloc_code);
                    return false;
                }

            }


        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }


        return parent::beforeSave();
    }

    public function afterSave()
    {
        Yii::app()->Helpers->saveLog($this);
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }


    public function getCreatedUserName()
    {
        if ($this->created_user_id != null) {
            $user = User::model()->findByPk($this->created_user_id);
            if ($user !== null) {
                return $user->name;
            }
        }

        return null;
    }

    public function getUpdatedUserName()
    {
        if ($this->updated_user_id != null) {
            $user = User::model()->findByPk($this->updated_user_id);
            if ($user !== null) {
                return $user->name;
            }
        }

        return null;
    }
}
