<?php

/**
 * This is the model class for table "order_klett".
 *
 * The followings are the available columns in table 'order_klett':
 * @property integer $id
 * @property integer $order_main_id
 * @property string $SenderID
 * @property string $SenderName
 * @property string $RecipientID
 * @property string $RecipientName
 * @property string $WarehouseID
 * @property string $OrderNo
 * @property string $OrderYear
 * @property string $LoadListNo
 * @property string $OrderDate
 * @property string $OrderTypeID
 * @property string $WMSOrderNo
 * @property string $RefInfNo
 * @property integer $IsSum
 * @property string $StockFrom
 * @property string $StockTo
 * @property string $OrderStatusID
 * @property string $VehicleData
 * @property string $DriverData
 * @property string $ULInstr
 * @property string $Remark
 * @property integer $PalettCount
 * @property integer $PackCount
 * @property string $OrderWeight
 * @property integer $IsWeb
 * @property integer $DocPrint
 * @property integer $PrintCopyNo
 * @property string $DeliveryType
 * @property integer $IsUrgent
 * @property string $RefDocType
 * @property string $DeliveryDate
 * @property string $CVParty
 * @property string $ShipToParty
 * @property string $Shipper
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderKlettItem[] $orderKlettItems
 * @property OrderKlettTransUnit[] $orderKlettTransUnits
 */
class OrderKlett extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OrderKlett the static model class
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
        return 'order_klett';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('SenderID, OrderNo, OrderYear, LoadListNo', 'required'),
            array('order_main_id, IsSum, PalettCount, PackCount, IsWeb, DocPrint, PrintCopyNo, IsUrgent, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('SenderID, RecipientID', 'length', 'max' => 9),
            array('SenderName, RecipientName', 'length', 'max' => 255),
            array('WarehouseID, OrderTypeID, StockFrom, StockTo, OrderStatusID, DeliveryType', 'length', 'max' => 2),
            array('OrderNo, LoadListNo', 'length', 'max' => 20),
            array('OrderYear, RefDocType', 'length', 'max' => 4),
            array('WMSOrderNo, RefInfNo', 'length', 'max' => 32),
            array('VehicleData, DriverData', 'length', 'max' => 80),
            array('ULInstr', 'length', 'max' => 100),
            array('Remark', 'length', 'max' => 200),
            array('OrderWeight', 'length', 'max' => 15),
            array('CVParty, ShipToParty, Shipper, OrderDate, DeliveryDate, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, order_main_id, SenderID, SenderName, RecipientID, RecipientName, WarehouseID, OrderNo, OrderYear, LoadListNo, OrderDate, OrderTypeID, WMSOrderNo, RefInfNo, IsSum, StockFrom, StockTo, OrderStatusID, VehicleData, DriverData, ULInstr, Remark, PalettCount, PackCount, OrderWeight, IsWeb, DocPrint, PrintCopyNo, DeliveryType, IsUrgent, RefDocType, DeliveryDate, CVParty, ShipToParty, Shipper, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'orderKlettItems' => array(self::HAS_MANY, 'OrderKlettItem', 'order_klett_id'),
            'orderKlettTransUnits' => array(self::HAS_MANY, 'OrderKlettTransUnit', 'order_klett_id'),
            'webOrder' => array(self::HAS_ONE,'WebOrder','order_klett_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'order_main_id' => Yii::t('app', 'Order Main'),
            'SenderID' => Yii::t('app', 'Sender'),
            'SenderName' => Yii::t('app', 'Sender Name'),
            'RecipientID' => Yii::t('app', 'Recipient'),
            'RecipientName' => Yii::t('app', 'Recipient Name'),
            'WarehouseID' => Yii::t('app', 'Warehouse'),
            'OrderNo' => Yii::t('app', 'Order No'),
            'OrderYear' => Yii::t('app', 'Order Year'),
            'LoadListNo' => Yii::t('app', 'Load List No'),
            'OrderDate' => Yii::t('app', 'Order Date'),
            'OrderTypeID' => Yii::t('app', 'Order Type'),
            'WMSOrderNo' => Yii::t('app', 'Wmsorder No'),
            'RefInfNo' => Yii::t('app', 'Ref Inf No'),
            'IsSum' => Yii::t('app', 'Is Sum'),
            'StockFrom' => Yii::t('app', 'Stock From'),
            'StockTo' => Yii::t('app', 'Stock To'),
            'OrderStatusID' => Yii::t('app', 'Order Status'),
            'VehicleData' => Yii::t('app', 'Vehicle Data'),
            'DriverData' => Yii::t('app', 'Driver Data'),
            'ULInstr' => Yii::t('app', 'Ulinstr'),
            'Remark' => Yii::t('app', 'Remark'),
            'PalettCount' => Yii::t('app', 'Palett Count'),
            'PackCount' => Yii::t('app', 'Pack Count'),
            'OrderWeight' => Yii::t('app', 'Order Weight'),
            'IsWeb' => Yii::t('app', 'Is Web'),
            'DocPrint' => Yii::t('app', 'Doc Print'),
            'PrintCopyNo' => Yii::t('app', 'Print Copy No'),
            'DeliveryType' => Yii::t('app', 'Delivery Type'),
            'IsUrgent' => Yii::t('app', 'Is Urgent'),
            'RefDocType' => Yii::t('app', 'Ref Doc Type'),
            'DeliveryDate' => Yii::t('app', 'Delivery Date'),
            'CVParty' => Yii::t('app', 'Cvparty'),
            'ShipToParty' => Yii::t('app', 'Ship To Party'),
            'Shipper' => Yii::t('app', 'Shipper'),
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
        $criteria->compare('order_main_id', $this->order_main_id);
        $criteria->compare('SenderID', $this->SenderID, true);
        $criteria->compare('SenderName', $this->SenderName, true);
        $criteria->compare('RecipientID', $this->RecipientID, true);
        $criteria->compare('RecipientName', $this->RecipientName, true);
        $criteria->compare('WarehouseID', $this->WarehouseID, true);
        $criteria->compare('OrderNo', $this->OrderNo, true);
        $criteria->compare('OrderYear', $this->OrderYear, true);
        $criteria->compare('LoadListNo', $this->LoadListNo, true);
        $criteria->compare('OrderDate', $this->OrderDate, true);
        $criteria->compare('OrderTypeID', $this->OrderTypeID, true);
        $criteria->compare('WMSOrderNo', $this->WMSOrderNo, true);
        $criteria->compare('RefInfNo', $this->RefInfNo, true);
        $criteria->compare('IsSum', $this->IsSum);
        $criteria->compare('StockFrom', $this->StockFrom, true);
        $criteria->compare('StockTo', $this->StockTo, true);
        $criteria->compare('OrderStatusID', $this->OrderStatusID, true);
        $criteria->compare('VehicleData', $this->VehicleData, true);
        $criteria->compare('DriverData', $this->DriverData, true);
        $criteria->compare('ULInstr', $this->ULInstr, true);
        $criteria->compare('Remark', $this->Remark, true);
        $criteria->compare('PalettCount', $this->PalettCount);
        $criteria->compare('PackCount', $this->PackCount);
        $criteria->compare('OrderWeight', $this->OrderWeight, true);
        $criteria->compare('IsWeb', $this->IsWeb);
        $criteria->compare('DocPrint', $this->DocPrint);
        $criteria->compare('PrintCopyNo', $this->PrintCopyNo);
        $criteria->compare('DeliveryType', $this->DeliveryType, true);
        $criteria->compare('IsUrgent', $this->IsUrgent);
        $criteria->compare('RefDocType', $this->RefDocType, true);
        $criteria->compare('DeliveryDate', $this->DeliveryDate, true);
        $criteria->compare('CVParty', $this->CVParty, true);
        $criteria->compare('ShipToParty', $this->ShipToParty, true);
        $criteria->compare('Shipper', $this->Shipper, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');
            $this->OrderNo = $this->setOrderNo();
        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }


    public function setOrderNo()
    {
        $order_no = $this->OrderNo;
        $duplicate = $this->findByAttributes(array("OrderNo" => $order_no));
        if ($duplicate && $duplicate->id != $this->id && $duplicate->OrderNo != '') {

            $cnt = 1;
            do {
                $tmp_order_no = $order_no . "-" . $cnt;
                $cnt++;
            } while ($this->findByAttributes(array("OrderNo" => $tmp_order_no)));
            return $tmp_order_no;
        } else {
            return $order_no;
        }
    }

    public function isWebOrder()
    {
/*
        $web_orders = array(
            '3110',
            '31E0',
            '31T0',
            '3810',
            '31F0'
        );
*/
        $web_orders = array(
            'WEB0',
            'TEL0',
        );
        $substr = trim($this->RefDocType);

        if (in_array($substr, $web_orders)) {
            return true;
        }

        return false;
    }

    public function getDeliveryType()
    {
        $delivery_type = (int)$this->DeliveryType;


        $delivery_types = self::deliveryTypes();

        if (isset($delivery_types[$delivery_type])) {
            return $delivery_types[$delivery_type];
        }

        return '';
    }

    public static function deliveryTypes()
    {
        return array(
            1 => 'POŠTA',
            2 => 'Lično preuzimanje',
            4 => 'Lična dostava',
            5 => 'BEX Express',
            6 => 'Post Export',
            7 => 'OTKUP',
            9 => 'D-Express',
            11 => 'Isporuka saradniku D-Express',
            14 => 'D-Express na račun primaoca',
            15 => 'Brza pošta po nalogu kupca',
            16 => 'Kancelarija',
            17 => 'D-otkup',
            21 => 'Isporuka saradniku - lično preuzimanje',
            31 => 'Isporuka saradniku - lična dostava',

        );
    }


}
