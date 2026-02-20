<?php

/**
 * This is the model class for table "web_order".
 *
 * The followings are the available columns in table 'web_order':
 * @property integer $id
 * @property integer $order_klett_id
 * @property string $order_number
 * @property integer $client_id
 * @property string $customer_data
 * @property integer $status
 * @property string $delivery_type
 * @property string $delivery_date
 * @property string $load_list
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property WebOrderProduct[] $webOrderProducts
 */
class WebOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'web_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_klett_id, order_number, client_id', 'required'),
			array('order_klett_id, client_id, status, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('order_number, delivery_type, load_list', 'length', 'max'=>255),
			array('delivery_date, customer_data, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_klett_id, order_number, client_id, customer_data, delivery_date, status, load_list, delivery_type, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'webOrderProducts' => array(self::HAS_MANY, 'WebOrderProduct', 'web_order_id'),
            'client' => array(self::BELONGS_TO,'Client','client_id'),
            'orderKlett' => array(self::BELONGS_TO,'OrderKlett','order_klett_id'),
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
			'order_number' => Yii::t('app','Order Number'),
			'client_id' => Yii::t('app','Client'),
			'customer_data' => Yii::t('app','Customer Data'),
			'delivery_date' => Yii::t('app','Delivery Date'),
			'status' => Yii::t('app','Status'),
			'delivery_type' => Yii::t('app','Delivery Type'),
			'load_list' => Yii::t('app','Load List'),
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
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('customer_data',$this->customer_data,true);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('load_list',$this->load_list,true);
		$criteria->compare('delivery_type',$this->delivery_type,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array('pageSize' => 100),
            'sort' => array('defaultOrder'=>'id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WebOrder the static model class
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
        $this->delivery_date = ($this->delivery_date == '' || $this->delivery_date == null) ? null : date('Y-m-d',strtotime($this->delivery_date));
        Yii::app()->Helpers->saveLog($this);

		return parent::beforeSave();
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

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function getActiveSlocs()
    {
        $active_slocs = array();
        $active_paletts = array();

        foreach ($this->webOrderProducts as $web_order_product) {

            $slocs_has_product = SlocHasProduct::model()->findAllByAttributes(array('product_id' => $web_order_product->product_id));


            foreach ($slocs_has_product as $sloc_has_product) {
                if ($sloc_has_product->getRealQuantity(true) >= $web_order_product->quantity) {
                    $sloc_has_product_id = $sloc_has_product->id;
                    break;
                }
            }

            if (isset($sloc_has_product_id)) {
                $active_slocs[] = array(
                    'web_order_id' => $this->id,
                    'web_order_product_id' => $web_order_product->id,
                    'sloc_id' => $sloc_has_product->sloc_id,
                    'sloc_code' => $sloc_has_product->sloc_code,
                    'activity_palett_id' => NULL,
                    'sscc_source' => NULL,
                    'product_id' => $web_order_product->product_id,
                    'product_barcode' => $web_order_product->product ? $web_order_product->product->product_barcode : '',
                    'target' => $web_order_product->quantity,
                );
                unset($sloc_has_product_id);
            } else {

                $activity_paletts_has_product = ActivityPalettHasProduct::model()->findAllByAttributes(array('product_id' => $web_order_product->product_id));
                foreach ($activity_paletts_has_product as $activity_palett_has_product) {
                    if ($activity_palett_has_product->activityPalett->isLocated() && $activity_palett_has_product->stockQuantity >= $web_order_product->quantity) {
                        $activity_palett_has_product_id = $activity_palett_has_product->id;
                        break;
                    }
                }
                if (isset($activity_palett_has_product_id)) {
                    $active_paletts[] = array(
                        'web_order_id' => $this->id,
                        'web_order_product_id' => $web_order_product->id,
                        'sloc_id' => $activity_palett_has_product->activityPalett->inSloc->sloc_id,
                        'sloc_code' => $activity_palett_has_product->activityPalett->inSloc->sloc_code,
                        'activity_palett_id' => $activity_palett_has_product->activity_palett_id,
                        'sscc_source' => $activity_palett_has_product->sscc,
                        'product_id' => $web_order_product->product_id,
                        'product_barcode' => $web_order_product->product ? $web_order_product->product->product_barcode : '',
                        'target' => $web_order_product->quantity,
                    );
                }
                unset($activity_palett_has_product_id);
            }

        }
        return array_merge($active_slocs,$active_paletts);

    }

    public function getTotalWeight()
    {
        $total_weight = 0;
        foreach ($this->webOrderProducts as $web_order_product) {



                $total_weight += $web_order_product->quantity * $web_order_product->product->weight;


        }

        return $total_weight;
    }
}
