<?php

/**
 * This is the model class for table "activity_palett_has_product_log".
 *
 * The followings are the available columns in table 'activity_palett_has_product_log':
 * @property integer $id
 * @property integer $activity_palett_has_product_id
 * @property integer $activity_palett_id
 * @property string $sscc
 * @property integer $product_id
 * @property string $product_barcode
 * @property string $product_info
 * @property integer $quantity
 * @property integer $packages
 * @property integer $units
 * @property string $reason
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 */
class ActivityPalettHasProductLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_palett_has_product_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('quantity, packages, units, reason','required'),
			array('activity_palett_has_product_id, activity_palett_id, product_id, quantity, packages, units, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('sscc, product_barcode', 'length', 'max'=>255),
			array('product_info, reason, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, activity_palett_has_product_id, activity_palett_id, sscc, product_id, product_barcode, product_info, quantity, packages, units, reason, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'activity_palett_has_product_id' => Yii::t('app','Activity Palett Has Product'),
			'activity_palett_id' => Yii::t('app','Activity Palett'),
			'sscc' => Yii::t('app','Sscc'),
			'product_id' => Yii::t('app','Product'),
			'product_barcode' => Yii::t('app','Product Barcode'),
			'product_info' => Yii::t('app','Product Info'),
			'quantity' => Yii::t('app','Quantity'),
			'packages' => Yii::t('app','Packages'),
			'units' => Yii::t('app','Van pakovanja'),
			'reason' => Yii::t('app','Reason'),
			'created_user_id' => Yii::t('app','Created User'),
			'created_dt' => Yii::t('app','Created Dt'),
			'updated_user_id' => Yii::t('app','Updated User'),
			'updated_dt' => Yii::t('app','Updated Dt'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('activity_palett_has_product_id',$this->activity_palett_has_product_id);
		$criteria->compare('activity_palett_id',$this->activity_palett_id);
		$criteria->compare('sscc',$this->sscc,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_barcode',$this->product_barcode,true);
		$criteria->compare('product_info',$this->product_info,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('packages',$this->packages);
		$criteria->compare('units',$this->units);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityPalettHasProductLog the static model class
	 */
	public static function model($className=__CLASS__)
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
}
