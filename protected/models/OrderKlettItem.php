<?php

/**
 * This is the model class for table "order_klett_item".
 *
 * The followings are the available columns in table 'order_klett_item':
 * @property integer $id
 * @property integer $order_klett_id
 * @property integer $ItemNo
 * @property string $ProductCode
 * @property string $ProductName
 * @property string $ProductDescr
 * @property string $Barcode
 * @property string $QttOnPalett
 * @property string $QttInPack
 * @property string $PackBarcode
 * @property string $PalletBarcode
 * @property string $UnitWeight
 * @property string $Quantity
 * @property string $RealQuantity
 * @property string $MsrUnit
 * @property string $DamagedQuantity
 * @property string $DamageDescr
 * @property integer $PalettCount
 * @property integer $PackCount
 * @property string $ItemWeight
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderKlett $orderKlett
 */
class OrderKlettItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_klett_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_klett_id, ProductCode, QttOnPalett, QttInPack, ProductName, Barcode, UnitWeight, Quantity', 'required'),
			array('order_klett_id, ItemNo, PalettCount, PackCount, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('ProductCode, Barcode, PackBarcode, PalletBarcode', 'length', 'max'=>20),
			array('ProductName', 'length', 'max'=>100),
			array('ProductDescr, DamageDescr', 'length', 'max'=>200),
			array('QttOnPalett, QttInPack, Quantity, RealQuantity, DamagedQuantity, ItemWeight', 'length', 'max'=>15),
			array('UnitWeight', 'length', 'max'=>12),
			array('MsrUnit', 'length', 'max'=>2),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_klett_id, ItemNo, ProductCode, ProductName, ProductDescr, Barcode, QttOnPalett, QttInPack, PackBarcode, PalletBarcode, UnitWeight, Quantity, RealQuantity, MsrUnit, DamagedQuantity, DamageDescr, PalettCount, PackCount, ItemWeight, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'ItemNo' => Yii::t('app','Item No'),
			'ProductCode' => Yii::t('app','Product Code'),
			'ProductName' => Yii::t('app','Product Name'),
			'ProductDescr' => Yii::t('app','Product Descr'),
			'Barcode' => Yii::t('app','Barcode'),
			'QttOnPalett' => Yii::t('app','Qtt On Pallet'),
			'QttInPack' => Yii::t('app','Qtt In Pack'),
			'PackBarcode' => Yii::t('app','Pack Barcode'),
			'PalletBarcode' => Yii::t('app','Pallet Barcode'),
			'UnitWeight' => Yii::t('app','Unit Weight'),
			'Quantity' => Yii::t('app','Quantity'),
			'RealQuantity' => Yii::t('app','Real Quantity'),
			'MsrUnit' => Yii::t('app','Msr Unit'),
			'DamagedQuantity' => Yii::t('app','Damaged Quantity'),
			'DamageDescr' => Yii::t('app','Damage Descr'),
			'PalettCount' => Yii::t('app','Palett Count'),
			'PackCount' => Yii::t('app','Pack Count'),
			'ItemWeight' => Yii::t('app','Item Weight'),
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
		$criteria->compare('ItemNo',$this->ItemNo);
		$criteria->compare('ProductCode',$this->ProductCode,true);
		$criteria->compare('ProductName',$this->ProductName,true);
		$criteria->compare('ProductDescr',$this->ProductDescr,true);
		$criteria->compare('Barcode',$this->Barcode,true);
		$criteria->compare('QttOnPalett',$this->QttOnPalett,true);
		$criteria->compare('QttInPack',$this->QttInPack,true);
		$criteria->compare('PackBarcode',$this->PackBarcode,true);
		$criteria->compare('PalletBarcode',$this->PalletBarcode,true);
		$criteria->compare('UnitWeight',$this->UnitWeight,true);
		$criteria->compare('Quantity',$this->Quantity,true);
		$criteria->compare('RealQuantity',$this->RealQuantity,true);
		$criteria->compare('MsrUnit',$this->MsrUnit,true);
		$criteria->compare('DamagedQuantity',$this->DamagedQuantity,true);
		$criteria->compare('DamageDescr',$this->DamageDescr,true);
		$criteria->compare('PalettCount',$this->PalettCount);
		$criteria->compare('PackCount',$this->PackCount);
		$criteria->compare('ItemWeight',$this->ItemWeight,true);
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
	 * @return OrderKlettItem the static model class
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

        /**
         *
         * AKO JE ORDER OUTBOUND, PROVERAVA DA LI PROIZVOD POSTOJI I DA LI GA IMA U DOVOLJNOJ KOLICINI
         *
         */


        if ($this->orderKlett->OrderTypeID == '02')
        {
            $product = Product::model()->findByAttributes(array('internal_product_number' => $this->ProductCode));

            if ($product === null) {
                $this->addError('ProductCode',Yii::t('app','Product does not exist.'));
                return false;
            }

            $product_stock = ProductStock::model()->findByAttributes(array('product_id'=>$product->id));
            if ($product_stock == null || $this->Quantity > $product_stock->quantity) {

                $total_products = $product->getTotalQuantity();
                if ($this->Quantity > $total_products) {
                    $this->addError('ProductCode', Yii::t('app', 'Not enough items. Max: ' . $product_stock->quantity));
                    return false;
                }

            }

        }

		return parent::beforeSave();
	}
}
