<?php

/**
 * This is the model class for table "order_product".
 *
 * The followings are the available columns in table 'order_product':
 * @property integer $id
 * @property integer $order_client_id
 * @property integer $product_id
 * @property integer $package_id
 * @property integer $quantity
 * @property integer $paletts
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderClient $orderClient
 * @property Product $product
 */
class OrderProduct extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_product';
	}

    public $order_request_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_client_id, product_id, quantity, paletts', 'required'),
			array('order_client_id, order_request_id, product_id, package_id, quantity, paletts, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_client_id, order_request_id, product_id, package_id, quantity, paletts, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'orderClient' => array(self::BELONGS_TO, 'OrderClient', 'order_client_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'package' => array(self::BELONGS_TO, 'Package', 'package_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'order_client_id' => Yii::t('app','Order Client'),
			'product_id' => Yii::t('app','Product'),
			'package_id' => Yii::t('app','Package'),
			'quantity' => Yii::t('app','Quantity'),
			'paletts' => Yii::t('app','Paletts'),
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
		$criteria->compare('order_client_id',$this->order_client_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('package_id',$this->package_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('paletts',$this->paletts);
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

    public function perOrder()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $sql = "SELECT id FROM order_client WHERE order_request_id = " . $this->order_request_id;
        $order_clients_ids = Yii::app()->db->createCommand($sql)->queryColumn();


        if (empty($order_clients_ids)) {
            $order_clients_ids = array(0);     /*** SET IMPOSSIBLE CONDITION */
        }

           $criteria->addInCondition('order_client_id',$order_clients_ids);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 9999,
            ),
            'sort' => array(
                'defaultOrder' => 'order_client_id'
            )
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function afterValidate()
    {
        if ($this->orderClient->orderRequest->direction == 'out') {
            /*
            $product = Product::model()->findByPk($this->product_id);
            if ($product === null) {
                throw new ChttpException('404','Product not found.');
            }
            $quantity = $product->getTotalQuantity();
            if ($this->quantity > $quantity) {
                $this->addError('quantity',Yii::t('app','Not enough products. Maximum allowed for picking: ') . $quantity);
                return false;
            }
            */
        }
        return parent::afterValidate();
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
        if ($this->orderClient->orderRequest->timeSlot) {
            $this->orderClient->orderRequest->timeSlot->order_request_id = NULL;
            $this->orderClient->orderRequest->timeSlot->save();
        }

        $product = Product::model()->findByPk($this->product_id);
        $this->package_id = $product->defaultPackage ? $product->defaultPackage->id : null;

		return parent::beforeSave();
	}
}
