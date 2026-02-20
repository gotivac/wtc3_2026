<?php

/**
 * This is the model class for table "order_client".
 *
 * The followings are the available columns in table 'order_client':
 * @property integer $id
 * @property integer $order_request_id
 * @property string $order_number
 * @property integer $client_id
 * @property integer $customer_supplier_id
 * @property string $delivery_type
 * @property string $delivery_date
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderRequest $orderRequest
 * @property OrderProduct[] $orderProducts
 */
class OrderClient extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_client';
	}

    public $order_request_ids = false;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_request_id,client_id,order_number', 'required'),
			array('order_request_id, client_id, customer_supplier_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('order_number, delivery_type', 'length', 'max'=>255),
			array('created_dt, updated_dt, delivery_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_request_id, order_request_ids, order_number, delivery_type, delivery_date, client_id, customer_supplier_id, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'orderRequest' => array(self::BELONGS_TO, 'OrderRequest', 'order_request_id'),
			'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'order_client_id'),
            'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
            'customerSupplier' => array(self::BELONGS_TO,'Client','customer_supplier_id'),
            'orderKlett' => array(self::HAS_ONE,'OrderKlett','order_main_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'order_request_id' => Yii::t('app','Order Request'),
			'order_number' => Yii::t('app','Order Number'),
			'client_id' => Yii::t('app','Client'),
			'customer_supplier_id' => Yii::t('app','Customer Supplier'),
			'delivery_type' => Yii::t('app','Delivery Type'),
			'delivery_date' => Yii::t('app','Delivery Date'),
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
        if ($this->order_request_ids) {
            $criteria->addInCondition('order_request_id',$this->order_request_ids);
        } else {
            $criteria->compare('order_request_id', $this->order_request_id);
        }
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('customer_supplier_id',$this->customer_supplier_id);
		$criteria->compare('delivery_type',$this->delivery_type);
		$criteria->compare('delivery_date',$this->delivery_date);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 100,
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderClient the static model class
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
        if ($this->orderRequest->timeSlot) {
            $this->orderRequest->timeSlot->order_request_id = NULL;
            $this->orderRequest->timeSlot->save();
        }
        if ($this->customer_supplier_id == '' || $this->customer_supplier_id == null) {
            $this->customer_supplier_id = $this->client_id;
        }

        Yii::app()->Helpers->saveLog($this);
        $this->delivery_date = ($this->delivery_date == '' || $this->delivery_date == null) ? null : date('Y-m-d',strtotime($this->delivery_date));
		return parent::beforeSave();


	}
    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }


    public function afterFind()
    {
        $this->delivery_date = ($this->delivery_date == '' || $this->delivery_date == null) ? null : date('d.m.Y',strtotime($this->delivery_date));
        return parent::afterFind();
    }

    public function afterSave()
    {
        $this->delivery_date = ($this->delivery_date == '' || $this->delivery_date == null) ? null : date('d.m.Y',strtotime($this->delivery_date));
        return parent::afterSave();
    }
    public function getTotalPaletts()
    {
        $sum = 0;
        foreach ($this->orderProducts as $order_product) {

            $sum += $order_product->paletts;

        }
        return $sum;

    }
}
