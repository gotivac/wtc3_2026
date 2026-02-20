<?php

/**
 * This is the model class for table "activity_order_control".
 *
 * The followings are the available columns in table 'activity_order_control':
 * @property integer $id
 * @property integer $activity_order_id
 * @property integer $activity_palett_id
 * @property string $sscc
 * @property integer $product_id
 * @property string $product_barcode
 * @property integer $quantity
 * @property integer $packages
 * @property integer $units
 * @property string $control_type
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ActivityOrder $activityOrder
 */
class ActivityOrderControl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_order_control';
	}

    public $activity_id = false;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activity_order_id, product_id, product_barcode', 'required'),
			array('activity_order_id, activity_palett_id, product_id, quantity, packages, units, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('sscc, product_barcode, control_type', 'length', 'max'=>255),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('activity_id, id, activity_order_id, activity_palett_id, sscc, product_id, product_barcode, quantity, packages, units, control_type, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'activityOrder' => array(self::BELONGS_TO, 'ActivityOrder', 'activity_order_id'),
            'product' => array(self::BELONGS_TO,'Product','product_id'),
            'createdUser' => array(self::BELONGS_TO,'User','created_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'activity_order_id' => Yii::t('app','Activity Order'),
			'activity_palett_id' => Yii::t('app','Activity Palett'),
			'sscc' => Yii::t('app','Sscc'),
			'product_id' => Yii::t('app','Product'),
			'product_barcode' => Yii::t('app','Product Barcode'),
			'quantity' => Yii::t('app','Kol.'),
			'packages' => Yii::t('app','Pak.'),
			'units' => Yii::t('app','Kom.'),
			'control_type' => Yii::t('app','Control Type'),
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

        if ($this->activity_id) {
            $sql = 'SELECT id FROM activity_order WHERE activity_id=' . $this->activity_id;
            $activity_order_ids = Yii::app()->db->createCommand($sql)->queryColumn();

            if (!empty($activity_order_ids)) {
                $criteria->addInCondition('activity_order_id',$activity_order_ids);
            } else {
                $criteria->compare('activity_order_id',0);
            }
        }



		$criteria->compare('activity_palett_id',$this->activity_palett_id);
		$criteria->compare('sscc',$this->sscc,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_barcode',$this->product_barcode,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('packages',$this->packages);
		$criteria->compare('units',$this->units);
		$criteria->compare('control_type',$this->control_type,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 9999,
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityOrderControl the static model class
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
}
