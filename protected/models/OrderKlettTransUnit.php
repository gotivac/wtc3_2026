<?php

/**
 * This is the model class for table "order_klett_trans_unit".
 *
 * The followings are the available columns in table 'order_klett_trans_unit':
 * @property integer $id
 * @property integer $order_klett_id
 * @property string $transporation_unit_id
 * @property string $weight
 * @property string $ProductCode
 * @property string $OrderNo
 * @property string $OrderYear
 * @property string $Quantity
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderKlett $orderKlett
 */
class OrderKlettTransUnit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_klett_trans_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_klett_id', 'required'),
			array('order_klett_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('transporation_unit_id', 'length', 'max'=>14),
			array('weight, Quantity', 'length', 'max'=>15),
			array('ProductCode, OrderNo', 'length', 'max'=>20),
			array('OrderYear', 'length', 'max'=>4),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_klett_id, transporation_unit_id, weight, ProductCode, OrderNo, OrderYear, Quantity, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'orderKlett' => array(self::BELONGS_TO, 'OrderKlett', 'order_klett_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'order_klett_id' => Yii::t('app','Order Klett'),
			'transporation_unit_id' => Yii::t('app','Transporation Unit'),
			'weight' => Yii::t('app','Weight'),
			'ProductCode' => Yii::t('app','Product Code'),
			'OrderNo' => Yii::t('app','Order No'),
			'OrderYear' => Yii::t('app','Order Year'),
			'Quantity' => Yii::t('app','Quantity'),
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
		$criteria->compare('order_klett_id',$this->order_klett_id);
		$criteria->compare('transporation_unit_id',$this->transporation_unit_id,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('ProductCode',$this->ProductCode,true);
		$criteria->compare('OrderNo',$this->OrderNo,true);
		$criteria->compare('OrderYear',$this->OrderYear,true);
		$criteria->compare('Quantity',$this->Quantity,true);
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
	 * @return OrderKlettTransUnit the static model class
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
