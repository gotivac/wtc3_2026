<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property integer $id
 * @property integer $order_request_id
 * @property integer $activity_type_id
 * @property string $direction
 * @property integer $location_id
 * @property integer $gate_id
 * @property string $truck_arrived_date
 * @property string $truck_arrived_time
 * @property string $truck_arrived_datetime
 * @property integer $truck_checked
 * @property string $truck_checked_date
 * @property string $truck_checked_time
 * @property string $truck_checked_datetime
 * @property string $truck_dispatch_date
 * @property string $truck_dispatch_time
 * @property string $truck_dispatch_datetime
 * @property string $license_plate
 * @property string $shipper_data
 * @property string $driver_data
 * @property string $payer_data
 * @property integer $driver_present
 * @property integer $system_acceptance
 * @property string $system_acceptance_datetime
 * @property string $customs
 * @property string $customs_datetime
 * @property integer $customs_user_id
 * @property string $notes
 * @property integer $urgent
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ActivityOrder[] $activityOrders
 * @property ActivityOrderProduct[] $activityOrderProducts
 * @property ActivityPalett[] $activityPaletts
 */
class Activity extends CActiveRecord
{
    public $old_customs;

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Activity the static model class
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
        return 'activity';
    }

    public $order_number_search = false;
    public $order_id_search = false;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order_request_id, location_id, gate_id, activity_type_id', 'required'),
            array('order_request_id, activity_type_id, location_id, gate_id, truck_checked, driver_present, system_acceptance, customs_user_id, urgent, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('direction, license_plate, customs', 'length', 'max' => 255),
            array('truck_arrived_date, truck_arrived_time, truck_arrived_datetime, truck_checked_date, truck_checked_time, shipper_data, driver_data, payer_data, truck_checked_datetime, truck_dispatch_date, truck_dispatch_time, truck_dispatch_datetime, system_acceptance_datetime, customs_datetime, notes, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, order_request_id, activity_type_id, direction, location_id, gate_id, shipper_data, driver_data, payer_data, truck_arrived_date, truck_arrived_time, truck_arrived_datetime, truck_checked, truck_checked_date, truck_checked_time, truck_checked_datetime, truck_dispatch_date, truck_dispatch_time, truck_dispatch_datetime, license_plate, driver_present, system_acceptance, system_acceptance_datetime, customs, customs_datetime, customs_user_id, notes, urgent, created_user_id, created_dt, updated_user_id, updated_dt, order_number_search, order_id_search', 'safe', 'on' => 'search'),
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
            'activityOrders' => array(self::HAS_MANY, 'ActivityOrder', 'activity_id'),
            'activityOrderProducts' => array(self::HAS_MANY, 'ActivityOrderProduct', 'activity_id'),
            'activityPaletts' => array(self::HAS_MANY, 'ActivityPalett', 'activity_id'),
            'orderRequest' => array(self::BELONGS_TO, 'OrderRequest', 'order_request_id'),
            'gate' => array(self::BELONGS_TO, 'Gate', 'gate_id'),
            'activityType' => array(self::BELONGS_TO, 'ActivityType', 'activity_type_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'attachments' => array(self::HAS_MANY, 'ActivityAttachment', 'activity_id'),

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'order_request_id' => Yii::t('app', 'Order Request'),
            'activity_type_id' => Yii::t('app', 'Activity Type'),
            'direction' => Yii::t('app', 'Direction'),
            'location_id' => Yii::t('app', 'Location'),
            'gate_id' => Yii::t('app', 'Gate'),
            'truck_arrived_date' => Yii::t('app', 'Truck Arrived Date'),
            'truck_arrived_time' => Yii::t('app', 'Truck Arrived Time'),
            'truck_arrived_datetime' => Yii::t('app', 'Truck Arrived Datetime'),
            'truck_checked' => Yii::t('app', 'Truck Checked'),
            'truck_checked_date' => Yii::t('app', 'Truck Checked Date'),
            'truck_checked_time' => Yii::t('app', 'Truck Checked Time'),
            'truck_checked_datetime' => Yii::t('app', 'Truck Checked Datetime'),
            'truck_dispatch_date' => Yii::t('app', 'Truck Dispatch Date'),
            'truck_dispatch_time' => Yii::t('app', 'Truck Dispatch Time'),
            'truck_dispatch_datetime' => Yii::t('app', 'Truck Dispatch Datetime'),
            'license_plate' => Yii::t('app', 'License Plate'),
            'shipper_data' => Yii::t('app', 'Shipper Data'),
            'driver_data' => Yii::t('app', 'Driver Data'),
            'payer_data' => Yii::t('app', 'Payer Data'),
            'driver_present' => Yii::t('app', 'Driver Present'),
            'system_acceptance' => Yii::t('app', 'System Acceptance'),
            'system_acceptance_datetime' => Yii::t('app', 'System Acceptance / Delivery Datetime'),
            'customs' => Yii::t('app', 'Customs'),
            'customs_datetime' => Yii::t('app', 'Customs Datetime'),
            'customs_user_id' => Yii::t('app', 'Customs User'),
            'notes' => Yii::t('app', 'Notes'),
            'urgent' => Yii::t('app', 'Urgent'),
            'created_user_id' => Yii::t('app', 'Created User'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'updated_user_id' => Yii::t('app', 'Updated User'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'order_number_search' => Yii::t('app', 'Orders'),
            'order_id_search' => Yii::t('app', 'Delivery Notice Number'),
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


        $related_filter = false;
        if ($this->order_number_search) {
            $sql = 'SELECT activity_id FROM activity_order WHERE order_number LIKE "%' . $this->order_number_search . '%"';
            $ids_order_number = Yii::app()->db->createCommand($sql)->queryColumn();
            $related_filter = true;
        } else {
            $ids_order_number = false;
        }
        if ($this->order_id_search) {
            $sql = 'SELECT activity_id FROM activity_order WHERE id =' . $this->order_id_search;
            $ids_order_id = Yii::app()->db->createCommand($sql)->queryColumn();
            $related_filter = true;
        } else {
            $ids_order_id = false;
        }

        if ($ids_order_id && $ids_order_number) {
            $ids = array_intersect($ids_order_id, $ids_order_number);
        } else if ($ids_order_id) {
            $ids = $ids_order_id;
        } else if ($ids_order_number) {
            $ids = $ids_order_number;
        } else {
            $ids = false;
        }

        if ($related_filter) {
            if ($ids) {
                $criteria->addInCondition('id', $ids);
            } else {
                $criteria->compare('id', 0);
            }
        }
        $criteria->compare('order_request_id', $this->order_request_id);
        $criteria->compare('activity_type_id', $this->activity_type_id);
        $criteria->compare('direction', $this->direction, true);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('gate_id', $this->gate_id);
        $criteria->compare('DATE_FORMAT(truck_arrived_date,"%d.%m.%Y")', $this->truck_arrived_date, true);
        $criteria->compare('truck_arrived_time', $this->truck_arrived_time, true);
        $criteria->compare('truck_arrived_datetime', $this->truck_arrived_datetime, true);
        $criteria->compare('truck_checked', $this->truck_checked);
        $criteria->compare('truck_checked_date', $this->truck_checked_date, true);
        $criteria->compare('truck_checked_time', $this->truck_checked_time, true);
        $criteria->compare('truck_checked_datetime', $this->truck_checked_datetime, true);
        $criteria->compare('truck_dispatch_date', $this->truck_dispatch_date, true);
        $criteria->compare('truck_dispatch_time', $this->truck_dispatch_time, true);
        $criteria->compare('truck_dispatch_datetime', $this->truck_dispatch_datetime, true);
        $criteria->compare('license_plate', $this->license_plate, true);
        $criteria->compare('driver_present', $this->driver_present);
        $criteria->compare('system_acceptance', $this->system_acceptance);
        $criteria->compare('system_acceptance_datetime', $this->system_acceptance_datetime, true);
        $criteria->compare('customs', $this->customs, true);
        $criteria->compare('customs_datetime', $this->customs_datetime, true);
        $criteria->compare('customs_user_id', $this->customs_user_id);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('urgent', $this->urgent);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,

            ),
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    public function open()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->addCondition('truck_arrived_datetime IS NOT NULL AND truck_dispatch_datetime IS NULL');


        $criteria->compare('id', $this->id);
        $criteria->compare('activity_type_id', $this->activity_type_id);
        $criteria->compare('direction', $this->direction);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('system_acceptance', 0);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            )
        ));
    }

    public function inbound()
    {
        $criteria = new CDbCriteria;

        $unlocated = ActivityPalett::model()->unlocated();

        if (count($unlocated) > 0) {
            $ids = array();
            foreach ($unlocated as $palett) {
                $ids[] = $palett->activity_id;
            }
            $criteria->addInCondition('id', $ids);
        } else {
            $criteria->compare('id', 0);
        }

        $criteria->compare('direction', $this->direction);
        $criteria->compare('location_id', $this->location_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            )
        ));

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

        if ($this->system_acceptance_datetime != '') {

            $can_close = true;

            foreach ($this->activityOrders as $activity_order) {
                if ($activity_order->status == 0) {
                    $can_close = false;
                }
            }
            if ($can_close) {
                $this->system_acceptance_datetime = date('Y-m-d H:i:s', strtotime($this->system_acceptance_datetime));
                $this->system_acceptance = 1;
            } else {
                $this->system_acceptance_datetime = null;
                $this->system_acceptance = 0;
                $this->addError('system_acceptance_datetime', 'Nalozi u okviru aktivnosti nisu zatvoreni.');
                return false;
            }

        } else {
            $this->system_acceptance_datetime = null;
            $this->system_acceptance = 0;

            if ($this->truck_dispatch_datetime != '' && $this->direction == 'out') {
                $this->addError('truck_dispatch_datetime', 'Vreme sistemskog prijema / isporuke ne može biti prazno');
                return false;
            }
        }

        if ($this->truck_arrived_datetime != '') {
            $this->truck_arrived_date = date('Y-m-d', strtotime($this->truck_arrived_datetime));
            $this->truck_arrived_time = date('H:i:s', strtotime($this->truck_arrived_datetime));
            $this->truck_arrived_datetime = date('Y-m-d H:i:s', strtotime($this->truck_arrived_datetime));
        } else {
            $this->truck_arrived_date = null;
            $this->truck_arrived_time = null;
            $this->truck_arrived_datetime = null;
        }

        if ($this->truck_dispatch_datetime != '') {
            $this->truck_dispatch_date = date('Y-m-d', strtotime($this->truck_dispatch_datetime));
            $this->truck_dispatch_time = date('H:i:s', strtotime($this->truck_dispatch_datetime));
            $this->truck_dispatch_datetime = date('Y-m-d H:i:s', strtotime($this->truck_dispatch_datetime));

            $this->loadPaletts();

        } else {
            $this->truck_dispatch_date = null;
            $this->truck_dispatch_time = null;
            $this->truck_dispatch_datetime = null;
        }


        if ($this->old_customs != $this->customs && $this->direction == 'in') {
            $this->customs_datetime = date('Y-m-d H:i:s');
            $this->customs_user_id = Yii::app()->user->id;
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        if ($this->truck_arrived_datetime != null) {
            $this->truck_arrived_datetime = date('d.m.Y H:i', strtotime($this->truck_arrived_datetime));
        }
        if ($this->truck_dispatch_datetime != null) {
            $this->truck_dispatch_datetime = date('d.m.Y H:i', strtotime($this->truck_dispatch_datetime));
        }
        if ($this->system_acceptance_datetime != null) {
            $this->system_acceptance_datetime = date('d.m.Y H:i', strtotime($this->system_acceptance_datetime));
        }

        if ($this->truck_arrived_date != null) {
            $this->truck_arrived_date = date('d.m.Y', strtotime($this->truck_arrived_date));
        }
        if ($this->truck_dispatch_date != null) {
            $this->truck_dispatch_date = date('d.m.Y', strtotime($this->truck_dispatch_date));
        }

        $this->old_customs = $this->customs;

        return parent::afterFind();
    }

    public function afterSave()
    {
        if ($this->truck_arrived_datetime != null) {
            $this->truck_arrived_datetime = date('d.m.Y H:i', strtotime($this->truck_arrived_datetime));
        }
        if ($this->truck_dispatch_datetime != null) {
            $this->truck_dispatch_datetime = date('d.m.Y H:i', strtotime($this->truck_dispatch_datetime));
        }
        if ($this->system_acceptance_datetime != null) {
            $this->system_acceptance_datetime = date('d.m.Y H:i', strtotime($this->system_acceptance_datetime));
        }

        if ($this->truck_arrived_date != null) {
            $this->truck_arrived_date = date('d.m.Y', strtotime($this->truck_arrived_date));
        }
        if ($this->truck_dispatch_date != null) {
            $this->truck_dispatch_date = date('d.m.Y', strtotime($this->truck_dispatch_date));
        }

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

    public function afterValidate()
    {

        if ($this->isNewRecord) {
            $double = $this->findByAttributes(array('order_request_id' => $this->order_request_id));
            if ($double) {
                $this->addError('activity_type_id', 'Već je kreirana aktivnost za ovaj nalog!');
                return false;
            } else {
                return parent::afterValidate();
            }
        }

        return parent::afterValidate();

    }


    public function createOrdersFromOrderRequest()
    {
        $order_request = $this->orderRequest;
        if ($order_request === null) {
            throw new CHttpException('404', 'Order not found.');
        }
        foreach ($order_request->orderClients as $order_client) {
            $activity_order = new ActivityOrder;
            $activity_order->attributes = array(
                'activity_id' => $this->id,
                'order_client_id' => $order_client->id,
                'order_number' => $order_client->order_number,
                'client_id' => $order_client->client_id,
                'customer_supplier_id' => $order_client->customer_supplier_id,
                'delivery_type' => $order_client->delivery_type,
            );
            if ($activity_order->save()) {
                foreach ($order_client->orderProducts as $order_product) {
                    $activity_order_product = new ActivityOrderProduct;
                    $activity_order_product->attributes = array(
                        'activity_id' => $this->id,
                        'activity_order_id' => $activity_order->id,
                        'order_product_id' => $order_product->id,
                        'product_id' => $order_product->product_id,
                        'package_id' => $order_product->package_id,
                        'products_in_package' => $order_product->product->defaultPackage ? $order_product->product->defaultPackage->product_count : 1,
                        'products_on_palett' => $order_product->product->defaultPackage ? $order_product->product->defaultPackage->product_count * $order_product->product->defaultPackage->load_carrier_count : 1,
                        'packages_on_palett' => $order_product->product->defaultPackage ? $order_product->product->defaultPackage->load_carrier_count : 1,
                        'quantity' => $order_product->quantity,
                        'paletts' => $order_product->paletts,

                    );
                    if ($activity_order_product->save()) {
                        // do nothing
                    } else {
                        $activity_order->activity->delete();
                        throw new CHttpException('500', strip_tags(CHtml::errorSummary($activity_order_product)));
                    }
                }
            }
        }
    }

    public function getTotalPaletts()
    {
        return count($this->activityPaletts);
    }

    public function generatePaletts()
    {
        foreach ($this->activityOrderProducts as $activity_order_product) {
            $activity_order_product->generatePaletts();
        }

    }

    public function getOrderNumber()
    {
        $order_number = array();
        foreach ($this->activityOrders as $activity_order) {
            $order_number[] = $activity_order->order_number;
        }
        if (count($order_number) > 1) {
            return $order_number;
        } else if (count($order_number) == 1) {
            return $order_number[0];
        }
        return false;
    }

    public function getScannedSSCCs()
    {
        $sql = 'SELECT DISTINCT sscc FROM activity_palett_has_product WHERE activity_palett_id IN (SELECT id FROM activity_palett WHERE activity_id=' . $this->id . ')';

        return Yii::app()->db->createCommand($sql)->queryColumn();
    }

    public function cleanScanned($all = false)
    {
        if ($all) {
            $sql = 'DELETE FROM activity_palett_has_product WHERE activity_palett_id IN (SELECT id FROM activity_palett WHERE activity_id=' . $this->id . ') AND product_id IS NULL';
        } else {
            $sql = 'DELETE FROM activity_palett_has_product WHERE activity_palett_id IN (SELECT id FROM activity_palett WHERE activity_id=' . $this->id . ') AND product_id IS NULL AND created_user_id=' . Yii::app()->user->id;
        }
        return Yii::app()->db->createCommand($sql)->execute();


    }

    public function getLocated()
    {
        return ActivityPalett::model()->findAll(array('condition' => 'activity_id=' . $this->id . ' AND id IN (SELECT activity_palett_id FROM sloc_has_activity_palett)'));
    }

    public function getUnlocated()
    {
        return ActivityPalett::model()->findAll(array('condition' => 'activity_id=' . $this->id . ' AND id NOT IN (SELECT activity_palett_id FROM sloc_has_activity_palett)'));
    }

    public function inboundIssues()
    {
        if (count($this->activityPaletts) > 0 && count($this->activityPaletts) > count($this->scannedSSCCs)) {
            return false;
        }

        $order_clients_ids = array();
        foreach ($this->orderRequest->orderClients as $order_client) {
            $order_clients_ids[] = $order_client->id;
        }
        if (!empty($order_clients_ids)) {

            $o_products = array();
            $sql = 'SELECT product_id, SUM(quantity) quantity FROM order_product WHERE order_client_id IN (' . implode(',', $order_clients_ids) . ') GROUP BY product_id';
            $order_products = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($order_products as $order_product) {
                $o_products[$order_product['product_id']] = $order_product['quantity'];
            }

            $activity_paletts_ids = array();

            foreach ($this->activityPaletts as $activity_palett) {
                foreach ($activity_palett->hasProducts as $activity_palett_has_products) {
                    $activity_paletts_ids[] = $activity_palett_has_products->activity_palett_id;
                }
            }
            if (!empty($activity_paletts_ids)) {
                $a_products = array();
                $sql = 'SELECT product_id, SUM(quantity) quantity FROM activity_palett_has_product WHERE activity_palett_id IN (' . implode(',', $activity_paletts_ids) . ') GROUP BY product_id';
                $activity_products = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($activity_products as $activity_product) {
                    $a_products[$activity_product['product_id']] = $activity_product['quantity'];
                }

                $difference = array();

                // echo '<pre>';var_dump($o_products,$a_products);die();
                foreach ($o_products as $key => $value) {
                    if (!isset($a_products[$key]) || $a_products[$key] != $value) {
                        $difference[] = array('product_id' => $key, 'order' => $value, 'activity' => $a_products[$key] ?? 0);
                    }
                }

                foreach ($a_products as $key => $value) {
                    if (!isset($o_products[$key])) {
                        $difference[] = array('product_id' => $key, 'order' => 0, 'activity' => $value);
                    }
                }

                if (!empty($difference)) {
                    return $difference;
                }

            }


        }

        return false;

    }

    /** IF IT IS INBOUND, ALL PALETTS NEED TO BE FILLED */
    /** IF IT IS OUTBOUND, ALL PICKS HAS TO BE STATUSED 1 */
    public function isReady()
    {

        if ($this->truck_dispatch_datetime != null) {
            return false;
        }

        if ($this->system_acceptance_datetime != null) {
            return true;


        }

        return false;
    }


    public function getBrutoWeight()
    {
        $result = 0;
        foreach ($this->activityPaletts as $activity_palett) {
            $result += $activity_palett->brutoWeight;
        }
        return $result;
    }

    public function loadPaletts()
    {
        $activity_orders = ActivityOrder::model()->findAll(array("condition" => "activity_id = " . $this->id));
        foreach ($activity_orders as $activity_order) {
            if ($activity_order->activity->direction == "in") {
                continue;
            }

            $picks = Pick::model()->findAllByAttributes(array('status' => 0, 'activity_order_id' => $activity_order->id));
            foreach ($picks as $pick) {
                if ($pick->sscc_destination == null) {
                    $pick->delete();
                    continue;
                }
                $load_group = strtotime(date('Y-m-d H:i:s'));
                $pick->load_group = $load_group;
                if ($pick->pick_type == 'palett') {
                    $sloc_has_activity_palett = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $pick->sscc_source));
                    $pick->status = 1;

                    if ($pick->save()) {
                        if ($sloc_has_activity_palett !== null) {
                            $sloc_has_activity_palett->delete();
                        }
                    }
                } else {
                    $pick->status = 1;
                    $pick->save();
                }

            }


        }
        return;
    }
}
