<?php

/**
 * This is the model class for table "activity_order".
 *
 * The followings are the available columns in table 'activity_order':
 * @property integer $id
 * @property integer $activity_id
 * @property integer $order_client_id
 * @property string $order_number
 * @property integer $client_id
 * @property integer $customer_supplier_id
 * @property integer $documents_ok
 * @property string $delivery_type
 * @property integer $status
 * @property string $notes
 * @property string $picking_list_dt
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Activity $activity
 * @property ActivityOrderProduct[] $activityOrderProducts
 * @property ActivityPalett[] $activityPaletts
 */
class ActivityOrder extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activity_order';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id, order_number, client_id', 'required'),
            array('activity_id, client_id, order_client_id, customer_supplier_id, documents_ok, created_user_id, updated_user_id, status', 'numerical', 'integerOnly' => true),
            array('order_number,delivery_type', 'length', 'max' => 255),
            array('notes, created_dt, updated_dt, picking_list_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity_id, order_number, delivery_type, client_id, order_client_id, customer_supplier_id, documents_ok, status, notes, picking_list_dt, delivery_type, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
            'activityOrderProducts' => array(self::HAS_MANY, 'ActivityOrderProduct', 'activity_order_id'),
            'activityPaletts' => array(self::HAS_MANY, 'ActivityPalett', 'activity_order_id'),
            'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
            'customerSupplier' => array(self::BELONGS_TO, 'Client', 'customer_supplier_id'),
            'orderClient' => array(self::BELONGS_TO, 'OrderClient', 'order_client_id'),


        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity'),
            'order_number' => Yii::t('app', 'Order Number'),
            'client_id' => Yii::t('app', 'Client'),
            'customer_supplier_id' => Yii::t('app', 'Customer Supplier'),
            'documents_ok' => Yii::t('app', 'Documents Ok'),
            'delivery_type' => Yii::t('app', 'Delivery Type'),
            'status' => Yii::t('app', 'Status'),
            'notes' => Yii::t('app', 'Notes'),
            'picking_list_dt' => Yii::t('app', 'Picking List Dt'),
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
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('order_number', $this->order_number, true);
        $criteria->compare('client_id', $this->client_id);
        $criteria->compare('customer_supplier_id', $this->customer_supplier_id);
        $criteria->compare('documents_ok', $this->documents_ok);
        $criteria->compare('delivery_type', $this->delivery_type, true);
        $criteria->compare('picking_list_dt', $this->picking_list_dt, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            )
        ));
    }

    public function byActivities()
    {


        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('order_number', $this->order_number, true);
        $criteria->compare('client_id', $this->client_id);
        $criteria->compare('customer_supplier_id', $this->customer_supplier_id);
        $criteria->compare('documents_ok', $this->documents_ok);
        $criteria->compare('status', $this->status);
        $criteria->compare('picking_list_dt', $this->picking_list_dt, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 999,
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivityOrder the static model class
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

                $double = $this->findByAttributes(array('order_client_id' => $this->order_client_id));
                if ($double) {
                    return false;
                }

        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        if ($this->customer_supplier_id == '' || $this->customer_supplier_id == null) {
            $this->customer_supplier_id = $this->client_id;
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        Yii::app()->Helpers->saveLog($this);
        if ($this->status==1) {
            $sql = 'DELETE FROM pick WHERE quantity=0 AND activity_order_id=' . $this->id;
            Yii::app()->db->createCommand($sql)->execute();
        }
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function getTotalProducts()
    {
        $product_count = 0;
        foreach ($this->activityOrderProducts as $activity_order_product) {
            $product_count += $activity_order_product->quantity;
        }
        return $product_count;
    }

    public function getTotalPickedProducts()
    {
        $product_count = 0;

        foreach ($this->getPicked() as $picked) {
            $product_count += $picked->quantity;
        }

        return $product_count;
    }

    public function getTotalPickedPackages()
    {
        $packages_count = 0;

        foreach ($this->getPicked() as $picked) {
            $packages_count += $picked->packages;
        }

        return $packages_count;
    }

    public function getPicked()
    {
        return Pick::model()->findAllByAttributes(array('activity_order_id' => $this->id));
    }

    public function getPickedSSCCs()
    {
        $sql = 'SELECT DISTINCT sscc_destination FROM pick WHERE sscc_destination IS NOT NULL AND activity_order_id = ' . $this->id;
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }

    public function getLoadedSSCCs()
    {
        $sql = 'SELECT DISTINCT sscc_destination FROM pick WHERE status = 1 AND activity_order_id = ' . $this->id;
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }

    public function getActiveOutboundActivityOrders($location_id)
    {
        $condition = 'location_id=' . $location_id;

        $condition .= ' AND direction="out" AND truck_dispatch_datetime IS NULL';
        $activities = Activity::model()->findAll(array('condition' => $condition));
        $activity_orders = array();
        foreach ($activities as $activity) {
            foreach ($activity->activityOrders as $activity_order) {
                $activity_orders[] = $activity_order;
            }
        }
        return $activity_orders;
    }
    public function getActiveSlocs()
    {
        $active_slocs = array();
        $active_paletts = array();

        foreach ($this->activityOrderProducts as $activity_order_product) {

            $existing = Pick::model()->findByAttributes(array('activity_order_id' => $this->id, 'product_id' => $activity_order_product->product_id));


            if ($existing) {
                continue;
            }

            $slocs_has_product = SlocHasProduct::model()->findAllByAttributes(array('product_id' => $activity_order_product->product_id));


            foreach ($slocs_has_product as $sloc_has_product) {
                if ($sloc_has_product->getRealQuantity(true) >= $activity_order_product->quantity) {
                    $sloc_has_product_id = $sloc_has_product->id;
                    break;
                }
            }

            if (isset($sloc_has_product_id)) {
                $active_slocs[] = array(
                    'web_order_id' => $this->id,
                    'web_order_product_id' => $activity_order_product->id,
                    'sloc_id' => $sloc_has_product->sloc_id,
                    'sloc_code' => $sloc_has_product->sloc_code,
                    'activity_palett_id' => NULL,
                    'sscc_source' => NULL,
                    'product_id' => $activity_order_product->product_id,
                    'product_barcode' => $activity_order_product->product ? $activity_order_product->product->product_barcode : '',
                    'target' => $activity_order_product->quantity,
                );
                unset($sloc_has_product_id);
            } else {

                $activity_paletts_has_product = ActivityPalettHasProduct::model()->findAllByAttributes(array('product_id' => $activity_order_product->product_id));
                foreach ($activity_paletts_has_product as $activity_palett_has_product) {

                    if ($activity_palett_has_product->activityPalett->inSloc->storageType->pickup == 0) {
                        continue;
                    }
                    if ($activity_palett_has_product->activityPalett->isLocated() && $activity_palett_has_product->stockQuantity >= $activity_order_product->quantity) {
                        $activity_palett_has_product_id = $activity_palett_has_product->id;
                        break;
                    }
                }
                if (isset($activity_palett_has_product_id)) {
                    $active_paletts[] = array(
                        'web_order_id' => $this->id,
                        'web_order_product_id' => $activity_order_product->id,
                        'sloc_id' => $activity_palett_has_product->activityPalett->inSloc->sloc_id,
                        'sloc_code' => $activity_palett_has_product->activityPalett->inSloc->sloc_code,
                        'activity_palett_id' => $activity_palett_has_product->activity_palett_id,
                        'sscc_source' => $activity_palett_has_product->sscc,
                        'product_id' => $activity_order_product->product_id,
                        'product_barcode' => $activity_order_product->product ? $activity_order_product->product->product_barcode : '',
                        'target' => $activity_order_product->quantity,
                    );
                }
                unset($activity_palett_has_product_id);
            }

        }
        return array_merge($active_slocs,$active_paletts);

    }

    public function getActivePaletts()
    {
        $active_paletts = array();
        foreach ($this->activityOrderProducts as $activity_order_product) {

            $existing = Pick::model()->findByAttributes(array('activity_order_id' => $this->id, 'product_id' => $activity_order_product->product_id));


            if ($existing) {
                continue;
            }

            $sql = 'SELECT * FROM activity_palett_has_product p WHERE product_id = ' . $activity_order_product->product_id;

            /* if ($activity_order->client->hasPickMethod('butch')) {
                $sql .= ' AND butch=' ???
            } */

            if ($this->client->hasPickMethod('FEFO')) {
                $sql .= ' ORDER BY expire_date ASC';
            } else if ($this->client->hasPickMethod('FIFO')) {
                $sql .= ' ORDER BY created_dt ASC';
            } else {

                $sql = 'SELECT p.* FROM activity_palett_has_product p JOIN sloc_has_activity_palett sp ON p.activity_palett_id = sp.activity_palett_id WHERE p.product_id = ' . $activity_order_product->product_id . ' ORDER BY RIGHT(sp.sloc_code,2) ASC, sp.sloc_code ASC';
            }

            $activity_paletts = Yii::app()->db->createCommand($sql)->queryAll();

            $product_count = 0;

            foreach ($activity_paletts as $activity_palett) {

                $activity_palett_model = ActivityPalett::model()->findByPk($activity_palett['activity_palett_id']);

                $activity_palett_has_product = ActivityPalettHasProduct::model()->findByPk($activity_palett['id']);

                $activity_palett['quantity'] = $activity_palett_has_product->stockQuantity;

                if (!$activity_palett_model->isLocated()) {
                    continue;
                }

                if ($activity_palett_model->inSloc->storageType->pickup == 0) {
                    continue;
                }

                if ($activity_palett['quantity'] <= 0) {
                    continue;
                }

                /*
                $picked = Pick::model()->findAll(array('condition' => 'product_id = ' . $activity_palett['product_id'] . ' AND sscc_source = "' . $activity_palett['sscc'] . '" AND activity_order_id !=' . $this->id));

                if ($activity_order_product->product_id == 2024) {
                    echo '<pre>';var_dump($activity_palett['quantity']);
                }
                if (count($picked) > 0) {
                    $picked_quantity = 0;
                    foreach ($picked as $pick) {
                        $picked_quantity += $pick->target;
                    }

                    $activity_palett['quantity'] = $activity_palett['quantity'] - $pick->target;
                }
                */


                $remains = $activity_order_product->quantity - $product_count; // preostalo da se pokupi

                if ($remains >= $activity_palett['quantity']) {
                    $pick_quantity = $activity_palett['quantity'];
                    $needed_quantity = $pick_quantity;
                    $product_count += $activity_palett['quantity'];
                    $condition = 'activity_palett_id=' . $activity_palett['activity_palett_id'] . ' AND product_id != ' . $activity_palett['product_id'];

                    /** ako ne postoje drugi proizvodi na paleti, pokupi paletu */

                    if (ActivityPalettHasProduct::model()->find(array('condition' => $condition)) === null) {
                        $pick_palett = true;
                    } else {
                        $pick_palett = false;
                    }


                    /** kraj ako ne postoje */

                } else if ($remains > 0) {
                    $pick_quantity = $remains;
                    $needed_quantity = $pick_quantity;
                    $product_count += $remains;
                    $pick_palett = false;

                } else {
                    $pick_quantity = false;
                    $needed_quantity = $activity_order_product->quantity - $product_count;
                    $pick_palett = null;
                }


                $sloc_id = SlocHasActivityPalett::model()->findByAttributes(array('sscc' => $activity_palett['sscc']))->sloc_id;
                $sloc = Sloc::model()->findByPk($sloc_id);


                $active_paletts[] = array_merge(
                    $activity_palett,
                    array(
                        'needed_quantity' => $needed_quantity,
                        'pick_quantity' => $pick_quantity,
                        'pick_palett' => $pick_palett,
                        'sloc_id' => $sloc_id,
                        'sloc_code' => $sloc->sloc_code
                    ));


            }

            $remains = $activity_order_product->quantity - $product_count;

            if ($remains > 0) {

                $active_paletts[] = array(
                    'id' => null,
                    'activity_palett_id' => null,
                    'product_id' => $activity_order_product->product->id,
                    'product_barcode' => $activity_order_product->product->product_barcode,
                    'quantity' => null,
                    'packages' => null,
                    'units' => null,
                    'expire_date' => null,
                    'batch' => null,
                    'needed_quantity' => $remains,
                    'pick_quantity' => false,
                );
            }


        }

        foreach ($active_paletts as $k => $active_palett) {
            if ($active_palett['pick_quantity'] === false) {
                unset($active_paletts[$k]);
            }
        }


        return $active_paletts;
    }

    public function getActivityPalettIds()
    {
        $sql = 'SELECT id FROM activity_palett WHERE activity_order_id=' . $this->id;
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }


    public function getPicks()
    {
        return Pick::model()->findAllByAttributes(array('activity_order_id' => $this->id));
    }

    public function createDeliveryNotice($pdf, $activity_palett_ids = false)
    {

        // if (!($this->activity->isReady() || ($this->activity->truck_dispatch_datetime != NULL))) {
        if ($this->status == 0 && !$activity_palett_ids) {
            throw new CHttpException('WTC', 'Nalog ' . $this->order_number . ' nije zatvoren.');
        }
        //  }
        $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
        $pdf->Image($logo_path, 15, 1, 5, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetFont("freesans", "B", 8);
        $pdf->MultiCell(6, 0.4, "DSV Road doo", 0, 'L', 0, 0, 1, 1, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(6, 0.4, "Dositejeva 45", 0, 'L', 0, 0, 1, 1.4, true);
        $pdf->MultiCell(6, 0.4, "22310 Šimanovci", 0, 'L', 0, 0, 1, 1.8, true);
        $pdf->MultiCell(6, 0.4, "Republika Srbija", 0, 'L', 0, 0, 1, 2.2, true);

        $pdf->SetFont("freesans", "B", 12);
        $pdf->MultiCell(6, 1, "OTPREMNICA", 0, 'L', 0, 0, 1, 2.8, true);
        $pdf->SetFont("freesans", "", 8);

        $order_client = OrderClient::model()->findByPk($this->order_client_id);
        if ($order_client !== null) {
            $order_klett = OrderKlett::model()->findByAttributes(array('order_main_id' => $order_client->id));
            if ($order_klett !== null) {

                $shipToParty = json_decode($order_klett->ShipToParty, true);

                $contact = isset($shipToParty['ShipToPartyContact']) ? (is_array($shipToParty['ShipToPartyContact']) ? '' : $shipToParty['ShipToPartyContact'] . ' - ') : '';
                $contact .= isset($shipToParty['ShipToPartyPhoneNo']) ? (is_array($shipToParty['ShipToPartyPhoneNo']) ? '' : $shipToParty['ShipToPartyPhoneNo']) : '';

                if (is_array($shipToParty)) {
                    $ship_to_party = array(
                        'title' => isset($shipToParty['ShipToPartyName']) ? (is_array($shipToParty['ShipToPartyName']) ? '' : $shipToParty['ShipToPartyName']) : '',
                        'address' => isset($shipToParty['ShipToPartyAddress']) ? (is_array($shipToParty['ShipToPartyAddress']) ? '' : $shipToParty['ShipToPartyAddress']) : '',
                        'postal_code' => isset($shipToParty['ShipToPartyPostalCode']) ? (is_array($shipToParty['ShipToPartyPostalCode']) ? '' : $shipToParty['ShipToPartyPostalCode']) : '',
                        'city' => isset($shipToParty['ShipToPartyCity']) ? (is_array($shipToParty['ShipToPartyCity']) ? '' : $shipToParty['ShipToPartyCity']) : '',
                        'pib' => isset($shipToParty['ShipToPartyTaxNo']) ? (is_array($shipToParty['ShipToPartyTaxNo']) ? '' : $shipToParty['ShipToPartyTaxNo']) : '',
                        'mb' => isset($shipToParty['ShipToPartyCompanyNo']) ? (is_array($shipToParty['ShipToPartyCompanyNo']) ? '' : $shipToParty['ShipToPartyCompanyNo']) : '',
                        'contact' => $contact,
                    );

                }
                $CVParty = json_decode($order_klett->CVParty, true);

                $contact = isset($CVParty['CVPartyContact']) ? (is_array($CVParty['CVPartyContact']) ? '' : $CVParty['CVPartyContact'] . ' - ') : '';
                $contact .= isset($CVParty['CVPartyPhoneNo']) ? (is_array($CVParty['CVPartyPhoneNo']) ? '' : $CVParty['CVPartyPhoneNo']) : '';

                if (is_array($CVParty)) {
                    $customer_supplier = array(
                        'title' => isset($CVParty['CVPartyName']) ? (is_array($CVParty['CVPartyName']) ? '' : $CVParty['CVPartyName']) : '',
                        'address' => isset($CVParty['CVPartyAddress']) ? (is_array($CVParty['CVPartyAddress']) ? '' : $CVParty['CVPartyAddress']) : '',
                        'postal_code' => isset($CVParty['CVPartyPostalCode'])  ? (is_array($CVParty['CVPartyPostalCode']) ? '' : $CVParty['CVPartyPostalCode']) : '',
                        'city' => isset($CVParty['CVPartyCity']) ? (is_array($CVParty['CVPartyCity']) ? '' : $CVParty['CVPartyCity']) : '',
                        'pib' => isset($CVParty['CVPartyTaxNo']) ? (is_array($CVParty['CVPartyTaxNo']) ? '' : $CVParty['CVPartyTaxNo']) : '',
                        'mb' => isset($CVParty['CVPartyCompanyNo']) ? (is_array($CVParty['CVPartyCompanyNo']) ? '' : $CVParty['CVPartyCompanyNo']) : '',
                        'contact' => $contact,
                    );

                }
            } else {
                $customer_supplier = [
                    'title' => $this->customerSupplier->title,
                    'address' => $this->customerSupplier->address,
                    'postal_code' => $this->customerSupplier->postal_code,
                    'city' => $this->customerSupplier->city,
                    'pib' => $this->customerSupplier->tax_number,
                    'mb' => $this->customerSupplier->company_number,
                    'contact'=>$this->customerSupplier->contact_person
                ];
                $ship_to_party = $customer_supplier;
            }
        }


        /*** DUPLIKATI */

        $this->order_number = substr($this->order_number, 0, 13);

        /*** END DUPLIKATI */


        $y = 3.5;
        $x = 1;
        /*
                $pdf->SetFont("dejavusans", "", 8);
                $pdf->MultiCell(3, 0.6, 'Broj:', 'LBRT', 'R', 0, 0, $x, $y,true,0,false,true,1.2,'M',true);
                $pdf->SetFont("dejavusans", "B", 9);
                $pdf->MultiCell(6, 0.6, $this->order_number.'-'.$this->id, 'LBRT', 'C', 0, 0, $x+3, $y,true,0,false,true,1.2,'M',true);
                $pdf->SetFont("dejavusans", "", 8);
        */

        $pdf->MultiCell(3, 0.6, "Broj:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->SetFont("freesans", "B", 9);
        $pdf->MultiCell(6, 0.6, $this->id, 'LBRT', 'C', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->SetFont("freesans", "", 8);
        $y += 0.6;

        $pdf->MultiCell(3, 0.6, "Datum:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, isset($order_klett) && $order_klett !== null ? date('d.m.Y', strtotime($order_klett->created_dt)) : "", 'TR', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);

        $y += 0.6;
        $pdf->MultiCell(3, 0.6, "Realizovano:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, $this->activity->truck_dispatch_datetime != null ? date("d.m.Y \u H:i", strtotime($this->activity->truck_dispatch_datetime)) : date("d.m.Y \u H:i"), 'TR', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.6;
        $pdf->MultiCell(3, 0.6, "Datum prometa:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, date("d.m.Y", strtotime($this->created_dt)), 'TR', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.6;
        $pdf->MultiCell(3, 0.6, "Tip zalihe:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, 'STANDARD', 'TR', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.6;
        $pdf->MultiCell(3, 0.6, "Ref dokument:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, isset($order_klett) && $order_klett !== null ? $order_klett->OrderYear . $this->order_number . ' / ' . date('d.m.Y', strtotime($order_klett->OrderDate)) : "", 'TRB', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.6;
        $pdf->MultiCell(3, 0.6, "Vrsta dostave:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, isset($order_klett) && $order_klett !== null ? $order_klett->deliveryType : "", 'TRB', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.6;
        $pdf->MultiCell(3, 1.6, "Info:", 'LBR', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 1.6, $this->notes, 'RB', 'L', 0, 0, $x + 3, $y, true);


        $x = 10;
        $y = 3.5;


        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(3, 1.6, "Kupac:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 1.6, 'T', true);
        $pdf->MultiCell(7, 0.4, isset($customer_supplier) ? $customer_supplier['title'] : '', 'TR', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($customer_supplier) ? $customer_supplier['address'] . ', ' . $customer_supplier['postal_code'] . ' ' . $customer_supplier['city'] : '', 'R', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($customer_supplier) ? ($customer_supplier['pib'] != '' ? 'PIB: ' . $customer_supplier['pib'] : '') . ($customer_supplier['mb'] != '' ? ' MB: ' . $customer_supplier['mb'] : '') : '', 'R', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($customer_supplier) ? 'Kontakt: ' . $customer_supplier['contact'] : '', 'R', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;

        $pdf->MultiCell(3, 1.2, "Vlasnik:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 1.2, 'T', true);
        $pdf->MultiCell(7, 0.4, $this->client->title, 'TR', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, $this->client->address . ', ' . $this->client->postal_code . ' ' . $this->client->city, 'R', 'L', 0, 0, $x + 3, $y, true);

        $y += 0.4;
        $pdf->MultiCell(7, 0.4, 'PIB: ' . $this->client->tax_number . ' MB: ' . $this->client->company_number, 'R', 'L', 0, 0, $x + 3, $y, true);

        $y += 0.4;
        $pdf->MultiCell(3, 1.6, "Primalac:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 1.6, 'T', true);
        $pdf->MultiCell(7, 0.4, isset($ship_to_party) ? $ship_to_party['title'] : '', 'TR', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($ship_to_party) ? $ship_to_party['address'] . ', ' . $ship_to_party['postal_code'] . ' ' . $ship_to_party['city'] : '', 'R', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($ship_to_party) ? ($ship_to_party['pib'] != '' ? 'PIB: ' . $ship_to_party['pib'] : '') . ($ship_to_party['mb'] != '' ? ' MB: ' . $ship_to_party['mb'] : '') : '', 'R', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;
        $pdf->MultiCell(7, 0.4, isset($ship_to_party) ? 'Kontakt: ' . $ship_to_party['contact'] : '', 'BR', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.4;

        $pdf->MultiCell(3, 0.8, "Prevoznik:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.8, 'T', true);

        if (isset($order_klett) && in_array($order_klett->DeliveryType, array('9', '11', '14', '17'))) {
            $shipper_data = 'YU PD Express d.o.o., Zage Malivuk 1, 11060 Beograd, PIB 101754136, MB 08192189';
        } else {
            $shipper_data = $this->activity->shipper_data;
        }

        $pdf->MultiCell(7, 0.8, $shipper_data, 'TR', 'L', 0, 0, $x + 3, $y, true);
        $y += 0.8;
        $pdf->MultiCell(3, 0.6, "Vozilo / Vozač:", 'LRTB', 'R', 0, 0, $x, $y, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(7, 0.6, $this->activity->license_plate . '/' . $this->activity->driver_data, 'TRB', 'L', 0, 0, $x + 3, $y, true, 0, false, true, 0.6, 'M', true);
        $y += 0.8;

        $x = 1;
        //       $pdf->MultiCell(19, 0.1, '', 'T', 'L', 0, 0, $x, $y, true);

        /** BARCODE  START */

        $barcode_value = '01-' . $this->id . '-' . date('Y');
        $y += 0.2;

        $x = 2.5;
        $width = 1600;
        $height = 800;
        $quality = Yii::app()->params['barcode']['quality'];
        $text = 1;
        $location = Yii::getPathOfAlias("webroot") . '/barcodes/delivery_notices/' . $this->order_number;

        barcode::Barcode39($this->order_number, $width, $height, $quality, $text, $location);
        $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/delivery_notices/' . $this->order_number;
        $pdf->Image($barcode_path, $x, $y, 6, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->MultiCell(6, 0.4, "* " . $barcode_value . " *", 0, 'C', 0, 0, $x, $y + 1, true);

        /** BARCODE END */
        $x = 1;
        $y -= 0.3;

        $pdf->SetFont("freesans", "B", 8);
        $pdf->MultiCell(2, 0.4, 'Instrukcija:', '', 'L', 0, 0, $x + 9, $y, true);
        $y += 0.4;
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(10.2, 1.3, $order_klett && $order_klett->ULInstr != '[]' ? $order_klett->ULInstr : '', '', 'L', 0, 0, $x + 9, $y, true);

        $y += 1.3;
        // $pdf->MultiCell(19, 0.1, '', 'T', 'L', 0, 0, $x, $y, true);


        // $pdf->SetFont("freesans", "B", 9);
        // $pdf->MultiCell(2, 0.6, date('d.m.Y', strtotime($this->picks[0]->created_dt)), 0, 'R', 0, 0, 18, 4.5, true);


        $y += 0.2;
        $pdf->SetFont("freesans", "B", 7);
        $x = 1;


        $pdf->MultiCell(1, 0.8, 'R.Br.', 'LRBT', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(1.8, 0.8, 'Šifra', 'LRBT', 'C', 0, 0, $x + 1, $y, true);
        $pdf->MultiCell(3.2, 0.8, 'Barkod', 'LRBT', 'C', 0, 0, $x + 2.8, $y, true);
        $pdf->MultiCell(6, 0.8, 'Naziv artikla', 'LRBT', 'C', 0, 0, $x + 6, $y, true);
        $pdf->MultiCell(1, 0.8, 'JM', 'LRBT', 'C', 0, 0, $x + 12, $y, true);


        $pdf->MultiCell(1.5, 0.8, 'Težina', 'LRBT', 'C', 0, 0, $x + 13, $y, true);
        $pdf->MultiCell(1.5, 0.8, 'Paketi', 'LRBT', 'C', 0, 0, $x + 14.5, $y, true);
        $pdf->MultiCell(1.5, 0.8, 'Palete', 'LRBT', 'C', 0, 0, $x + 16, $y, true);
        $pdf->MultiCell(1.5, 0.8, 'Količina', 'LRBT', 'C', 0, 0, $x + 17.5, $y, true);


        $y += 0.8;
        $pdf->SetFont("freesans", "", 7);
        $item_no = 1;

        $total_quantity = 0;
        $total_weight = 0;
        $palett_number = 0;

        $width = 800;
        $height = 100;
        $quality = Yii::app()->params['barcode']['quality'];
        $text = 0;
        $location = Yii::getPathOfAlias("webroot") . '/barcodes/delivery_notices/';


        foreach ($this->activityOrderProducts as $activity_order_product) {
            $quantity = 0;
            $weight = 0;
            foreach ($this->activityPaletts as $palett) {
                if ($activity_palett_ids && !in_array($palett->id, $activity_palett_ids)) {
                    continue;
                }
                foreach ($palett->hasProducts as $hasProduct) {
                    if ($hasProduct->product_id == $activity_order_product->product_id) {
                        $quantity += $hasProduct->quantity;
                        $weight += $hasProduct->quantity * $hasProduct->product->weight;
                    }
                }
            }
            if ($quantity > 0) {
                $pdf->MultiCell(1, 0.8, $item_no . '.', 'LRBT', 'R', 0, 0, $x, $y, true);
                $pdf->MultiCell(1.8, 0.8, $activity_order_product->product->internal_product_number, 'LRBT', 'L', 0, 0, $x + 1, $y, true);
                $pdf->MultiCell(3.2, 0.8, $activity_order_product->product->product_barcode, 'LRBT', 'L', 0, 0, $x + 2.8, $y, true);
                barcode::Barcode39($activity_order_product->product->product_barcode, $width, $height, $quality, $text, $location.$activity_order_product->product->product_barcode);
                $barcode_path = $location . $activity_order_product->product->product_barcode;
                $pdf->Image($barcode_path, $x+2.9, $y+0.3, 3, 0.4, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                $pdf->MultiCell(6, 0.8, $activity_order_product->product->title, 'LRBT', 'L', 0, 0, $x + 6, $y, true);
                $pdf->MultiCell(1, 0.8, 'KD', 'LRBT', 'L', 0, 0, $x + 12, $y, true);


                $pdf->MultiCell(1.5, 0.8, number_format($weight,0,",","."), 'LRBT', 'R', 0, 0, $x + 13, $y, true);
                $pdf->MultiCell(1.5, 0.8, 0, 'LRBT', 'R', 0, 0, $x + 14.5, $y, true);
                $pdf->MultiCell(1.5, 0.8, 0, 'LRBT', 'R', 0, 0, $x + 16, $y, true);
                $pdf->MultiCell(1.5, 0.8, number_format($quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17.5, $y, true);

                $y += 0.8;

                if ($y >= 27) {
                    $pdf->addPage();
                    $y = 1;
                }
                $item_no++;

                $total_quantity += $quantity;
                $total_weight += $weight;
            }


        }
        if ($y >= 27) {
            $pdf->addPage();
            $y = 1;
        }
        $pdf->SetFont("freesans", "B", 7);
        $pdf->MultiCell(13, 0.8, '', 'LRBT', 'L', 0, 0, $x, $y, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(1.5, 0.8, number_format($total_weight,0,",","."), 'LRBT', 'R', 0, 0, $x + 13, $y, true, 0, false, true, 0.8, 'M', true);

        $pdf->MultiCell(1.5, 0.8, 0, 'LRBT', 'R', 0, 0, $x + 14.5, $y, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(1.5, 0.8, 0, 'LRBT', 'R', 0, 0, $x + 16, $y, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(1.5, 0.8, number_format($total_quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17.5, $y, true, 0, false, true, 0.8, 'M', true);

        $y += 1;
        $pdf->SetFont("freesans", "", 9);
        $pdf->MultiCell(19, 1.5, 'Broj europaleta: ' . (is_array($this->activityPaletts) ? count($this->activityPaletts) : 0), 0, 'C', 0, 0, $x, $y, true);
        $y += 1;
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(4, 1.5, 'Robu primio', 'B', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(4, 1.5, 'Robu izdao', 'B', 'C', 0, 0, $x + 15, $y, true);
        return $pdf;
    }

    public function createDeliveryNoticeOld($pdf)
    {

        // if (!($this->activity->isReady() || ($this->activity->truck_dispatch_datetime != NULL))) {
        if ($this->status == 0) {
            throw new CHttpException('WTC', 'Nalog ' . $this->order_number . ' nije zatvoren.');
        }
        //  }
        $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
        $pdf->Image($logo_path, 15, 1, 5, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetFont("freesans", "B", 8);
        $pdf->MultiCell(6, 0.4, "DSV Road doo", 0, 'R', 0, 0, 14, 2, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(6, 0.4, "Dositejeva 45", 0, 'R', 0, 0, 14, 2.4, true);
        $pdf->MultiCell(6, 0.4, "22310 Šimanovci", 0, 'R', 0, 0, 14, 2.8, true);
        $pdf->MultiCell(6, 0.4, "Republika Srbija", 0, 'R', 0, 0, 14, 3.2, true);
        $pdf->SetFont("freesans", "B", 9);
        $pdf->MultiCell(6, 0.6, "VLASNIK", 'B', 'L', 0, 0, 1, 1, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(6, 0.4, $this->client->title, 0, 'L', 0, 0, 1, 1.8, true);
        $pdf->MultiCell(6, 0.4, $this->client->address, 0, 'L', 0, 0, 1, 2.2, true);
        $pdf->MultiCell(6, 0.4, $this->client->postal_code . ' ' . $this->client->city, 0, 'L', 0, 0, 1, 2.6, true);
        $pdf->MultiCell(6, 0.4, 'PIB: ' . $this->client->tax_number, 0, 'L', 0, 0, 1, 3, true);
        $pdf->MultiCell(6, 0.4, 'MB: ' . $this->client->company_number, 0, 'L', 0, 0, 1, 3.4, true);

        $pdf->MultiCell(19, 0.5, '', 'T', 'L', 0, 0, 1, 4, true);


        $order_client = OrderClient::model()->findByPk($this->order_client_id);
        if ($order_client !== null) {
            $order_klett = OrderKlett::model()->findByAttributes(array('order_main_id' => $order_client->id));
            if ($order_klett !== null) {

                $shipToParty = json_decode($order_klett->ShipToParty, true);

                $contact = isset($shipToParty['ShipToPartyContact']) ? (is_array($shipToParty['ShipToPartyContact']) ? '' : $shipToParty['ShipToPartyContact'] . ' - ') : '';
                $contact .= isset($shipToParty['ShipToPartyPhoneNo']) ? (is_array($shipToParty['ShipToPartyPhoneNo']) ? '' : $shipToParty['ShipToPartyPhoneNo']) : '';

                if (is_array($shipToParty)) {
                    $ship_to_party = array(
                        'title' => isset($shipToParty['ShipToPartyName']) ? (is_array($shipToParty['ShipToPartyName']) ? '' : $shipToParty['ShipToPartyName']) : '',
                        'address' => isset($shipToParty['ShipToPartyAddress']) ? (is_array($shipToParty['ShipToPartyAddress']) ? '' : $shipToParty['ShipToPartyAddress']) : '',
                        'postal_code' => isset($shipToParty['ShipToPartyPostalCode']) ? (is_array($shipToParty['ShipToPartyPostalCode']) ? '' : $shipToParty['ShipToPartyPostalCode']) : '',
                        'city' => isset($shipToParty['ShipToPartyCity']) ? (is_array($shipToParty['ShipToPartyCity']) ? '' : $shipToParty['ShipToPartyCity']) : '',
                        'pib' => isset($shipToParty['ShipToPartyTaxNo']) ? (is_array($shipToParty['ShipToPartyTaxNo']) ? '' : $shipToParty['ShipToPartyTaxNo']) : '',
                        'mb' => isset($shipToParty['ShipToPartyCompanyNo']) ? (is_array($shipToParty['ShipToPartyCompanyNo']) ? '' : $shipToParty['ShipToPartyCompanyNo']) : '',
                        'contact' => $contact,
                    );

                }
                $CVParty = json_decode($order_klett->CVParty, true);

                $contact = isset($CVParty['CVPartyContact']) ? (is_array($CVParty['CVPartyContact']) ? '' : $CVParty['CVPartyContact'] . ' - ') : '';
                $contact .= isset($CVParty['CVPartyPhoneNo']) ? (is_array($CVParty['CVPartyPhoneNo']) ? '' : $CVParty['CVPartyPhoneNo']) : '';

                if (is_array($CVParty)) {
                    $customer_supplier = array(
                        'title' => is_array($CVParty['CVPartyName']) ? '' : $CVParty['CVPartyName'],
                        'address' => is_array($CVParty['CVPartyAddress']) ? '' : $CVParty['CVPartyAddress'],
                        'postal_code' => is_array($CVParty['CVPartyPostalCode']) ? '' : $CVParty['CVPartyPostalCode'],
                        'city' => is_array($CVParty['CVPartyCity']) ? '' : $CVParty['CVPartyCity'],
                        'pib' => is_array($CVParty['CVPartyTaxNo']) ? '' : $CVParty['CVPartyTaxNo'],
                        'mb' => is_array($CVParty['CVPartyCompanyNo']) ? '' : $CVParty['CVPartyCompanyNo'],
                        'contact' => $contact,
                    );

                }
            }
        }

        /*** DUPLIKATI */

        $this->order_number = substr($this->order_number, 0, 13);

        /*** END DUPLIKATI */

        $pdf->SetFont("freesans", "B", 9);
        $pdf->MultiCell(3, 0.6, 'Primalac', 'B', 'L', 0, 0, 1, 4.5, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(2, 2, "Kupac", 'LRTB', 'L', 0, 0, 1, 5.1, true, 0, false, true, 2, 'M', true);
        $pdf->MultiCell(6, 0.4, isset($customer_supplier) ? $customer_supplier['title'] : '', 'TR', 'L', 0, 0, 3, 5.1, true);
        $pdf->MultiCell(6, 0.4, isset($customer_supplier) ? $customer_supplier['address'] : '', 'R', 'L', 0, 0, 3, 5.5, true);
        $pdf->MultiCell(6, 0.4, isset($customer_supplier) ? $customer_supplier['postal_code'] . ' ' . $customer_supplier['city'] : '', 'R', 'L', 0, 0, 3, 5.9, true);
        $pdf->MultiCell(6, 0.4, isset($customer_supplier) ? ($customer_supplier['pib'] != '' ? 'PIB: ' . $customer_supplier['pib'] : '') . ($customer_supplier['mb'] != '' ? ' MB: ' . $customer_supplier['mb'] : '') : '', 'R', 'L', 0, 0, 3, 6.3, true);
        $pdf->MultiCell(6, 0.4, isset($customer_supplier) ? 'Kontakt: ' . $customer_supplier['contact'] : '', 'R', 'L', 0, 0, 3, 6.7, true);
        $pdf->MultiCell(2, 2, "Primalac", 'LRTB', 'L', 0, 0, 1, 7.1, true, 0, false, true, 1.6, 'M', true);
        $pdf->MultiCell(6, 0.4, isset($ship_to_party) ? $ship_to_party['title'] : '', 'TR', 'L', 0, 0, 3, 7.1, true);
        $pdf->MultiCell(6, 0.4, isset($ship_to_party) ? $ship_to_party['address'] : '', 'R', 'L', 0, 0, 3, 7.5, true);
        $pdf->MultiCell(6, 0.4, isset($ship_to_party) ? $ship_to_party['postal_code'] . ' ' . $ship_to_party['city'] : '', 'R', 'L', 0, 0, 3, 7.9, true);
        $pdf->MultiCell(6, 0.4, isset($ship_to_party) ? ($ship_to_party['pib'] != '' ? 'PIB: ' . $ship_to_party['pib'] : '') . ($ship_to_party['mb'] != '' ? ' MB: ' . $ship_to_party['mb'] : '') : '', 'R', 'L', 0, 0, 3, 8.3, true);
        $pdf->MultiCell(6, 0.4, isset($ship_to_party) ? 'Kontakt: ' . $ship_to_party['contact'] : '', 'BR', 'L', 0, 0, 3, 8.7, true);

        $pdf->MultiCell(2, 0.8, "Prevoznik", 'LRTB', 'L', 0, 0, 1, 9.1, true, 0, false, true, 0.8, 'M', true);


        if (isset($order_klett) && in_array($order_klett->DeliveryType, array('9', '11', '14', '17'))) {
            $shipper_data = 'YU PD Express d.o.o., Zage Malivuk 1, 11060 Beograd, PIB 101754136, MB 08192189';
        } else {
            $shipper_data = $this->activity->shipper_data;
        }
        $pdf->MultiCell(6, 0.8, $shipper_data, 'TR', 'L', 0, 0, 3, 9.1, true);
        $pdf->MultiCell(2, 0.8, "Vozač", 'LRTB', 'L', 0, 0, 1, 9.9, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(6, 0.8, $this->activity->driver_data, 'TR', 'L', 0, 0, 3, 9.9, true);
        $pdf->MultiCell(2, 0.8, "Reg. br. vozila", 'LRTB', 'L', 0, 0, 1, 10.7, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(6, 0.8, $this->activity->license_plate, 'TRB', 'L', 0, 0, 3, 10.7, true);


        $pdf->SetFont("freesans", "B", 9);


        $pdf->MultiCell(9, 1.2, 'Broj naloga: ' . $this->order_number . '-' . $this->id, 'LBRT', 'C', 0, 0, 11, 5.1, true, 0, false, true, 1.2, 'M', true);
        $pdf->SetFont("freesans", "", 8);

        $pdf->MultiCell(3, 0.6, "Datum", 'LRTB', 'L', 0, 0, 11, 6.3, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(6, 0.6, isset($order_klett) && $order_klett !== null ? date('d.m.Y', strtotime($order_klett->created_dt)) : "", 'TR', 'R', 0, 0, 14, 6.3, true, 0, false, true, 0.6, 'M', true);

        $pdf->MultiCell(3, 0.6, "Realizovano", 'LRTB', 'L', 0, 0, 11, 6.9, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, $this->activity->truck_dispatch_datetime != null ? date("d.m.Y \u H:i", strtotime($this->activity->truck_dispatch_datetime)) : date("d.m.Y \u H:i"), 'TR', 'R', 0, 0, 14, 6.9, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(3, 0.6, "Datum prometa", 'LRTB', 'L', 0, 0, 11, 7.5, true, 0, false, true, 0.6, 'M', true);

        $pdf->MultiCell(6, 0.6, date("d.m.Y", strtotime($this->created_dt)), 'TR', 'R', 0, 0, 14, 7.5, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(3, 0.6, "Tip skladištenja", 'LRTB', 'L', 0, 0, 11, 8.1, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, 'STANDARD', 'TR', 'R', 0, 0, 14, 8.1, true, 0, false, true, 0.6, 'M', true);

        $pdf->MultiCell(3, 0.6, "Vrsta dostave", 'LRTB', 'L', 0, 0, 11, 8.7, true, 0, false, true, 0.6, 'M', true);
        $pdf->MultiCell(6, 0.6, isset($order_klett) && $order_klett !== null ? $order_klett->deliveryType : "", 'TRB', 'R', 0, 0, 14, 8.7, true, 0, false, true, 0.6, 'M', true);

        $pdf->MultiCell(3, 2.2, "Info", 'LRTB', 'L', 0, 0, 11, 9.3, true, 0, false, true, 0.6, 'M', true);

        $pdf->MultiCell(6, 2.2, $this->notes, 'TRB', 'L', 0, 0, 14, 9.3, true);

        // $pdf->SetFont("freesans", "B", 9);
        // $pdf->MultiCell(2, 0.6, date('d.m.Y', strtotime($this->picks[0]->created_dt)), 0, 'R', 0, 0, 18, 4.5, true);

        $pdf->MultiCell(19, 0.2, '', 'T', 'L', 0, 0, 1, 11.7, true);
        $pdf->SetFont("freesans", "B", 9);
        $pdf->MultiCell(5, 0.4, 'Otpremnica', 0, 'L', 0, 0, 1, 11.7, true);
        $pdf->SetFont("freesans", "", 9);
        $pdf->MultiCell(5, 0.4, $this->order_number . '-' . $this->id, 0, 'L', 0, 0, 1, 12.1, true);

        $width = 1600;
        $height = 800;
        $quality = Yii::app()->params['barcode']['quality'];
        $text = 1;

        $location = Yii::getPathOfAlias("webroot") . '/barcodes/delivery_notices/' . $this->order_number;
        barcode::Barcode39($this->order_number, $width, $height, $quality, $text, $location);
        $barcode_path = Yii::getPathOfAlias("webroot") . '/barcodes/delivery_notices/' . $this->order_number;
        $pdf->Image($barcode_path, 7.5, 2.2, 6, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->MultiCell(6, 0.4, "* " . $this->order_number . " *", 0, 'C', 0, 0, 7.5, 3.2, true);

        $pdf->MultiCell(19, 0.2, '', 'T', 'L', 0, 0, 1, 12.6, true);

        /*
        $pdf->SetFont("freesans", "B", 9);
        $pdf->SetFillColor(235, 235, 235);
        $pdf->MultiCell(19, 0.5, 'Otpremnica broj: ' . $this->id . '/' . date('Y', strtotime($this->activity->truck_arrived_date)), 'LRBT', 'L', 1, 0, 1, 5, true);
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(3, 0.5, 'Datum prijema', 'LRBT', 'C', 1, 0, 1, 5.5, true);
        $pdf->MultiCell(3, 0.5, 'Broj naloga', 'LRBT', 'C', 1, 0, 4, 5.5, true);
        $pdf->MultiCell(7, 0.5, 'Dobavljač', 'LRBT', 'C', 1, 0, 7, 5.5, true);
        $pdf->MultiCell(6, 0.5, 'Naziv klijenta', 'LRBT', 'C', 1, 0, 14, 5.5, true);

        $pdf->MultiCell(3, 1, date('d.m.Y', strtotime($this->activity->truck_arrived_date)), 'LRBT', 'C', 1, 0, 1, 6, true);
        $pdf->MultiCell(3, 1, $this->order_number, 'LRBT', 'C', 1, 0, 4, 6, true);
        $pdf->MultiCell(7, 1, $this->customerSupplier->title, 'LRBT', 'C', 1, 0, 7, 6, true);
        $pdf->MultiCell(6, 1, $this->client->title, 'LRBT', 'C', 1, 0, 14, 6, true);
*/

        $pdf->SetFont("freesans", "B", 7);
        $x = 1;
        $y = 12.8;

        $pdf->MultiCell(1, 0.8, 'R.Br.', 'LRBT', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(2, 0.8, 'Broj artikla', 'LRBT', 'C', 0, 0, $x + 1, $y, true);
        $pdf->MultiCell(6, 0.8, 'Opis artikla', 'LRBT', 'C', 0, 0, $x + 3, $y, true);
        $pdf->MultiCell(3, 0.8, 'Barkod', 'LRBT', 'C', 0, 0, $x + 9, $y, true);

        $pdf->MultiCell(3, 0.8, 'Šarža', 'LRBT', 'C', 0, 0, $x + 12, $y, true);

        $pdf->MultiCell(2, 0.8, 'Količina', 'LRBT', 'R', 0, 0, $x + 15, $y, true);
        $pdf->MultiCell(2, 0.8, 'Težina', 'LRBT', 'R', 0, 0, $x + 17, $y, true);


        $y += 0.8;
        $pdf->SetFont("freesans", "", 7);
        $item_no = 1;

        $total_quantity = 0;
        $total_weight = 0;
        foreach ($this->activityPaletts as $palett) {
            foreach ($palett->hasProducts as $hasProduct) {
                $pdf->MultiCell(1, 0.8, $item_no . '.', 'LRBT', 'R', 0, 0, $x, $y, true);
                $pdf->MultiCell(2, 0.8, $hasProduct->product->external_product_number, 'LRBT', 'L', 0, 0, $x + 1, $y, true);
                $pdf->MultiCell(6, 0.8, $hasProduct->product->title, 'LRBT', 'L', 0, 0, $x + 3, $y, true);
                $pdf->MultiCell(3, 0.8, $hasProduct->product->product_barcode, 'LRBT', 'L', 0, 0, $x + 9, $y, true);

                $pdf->MultiCell(3, 0.8, $hasProduct->batch, 'LRBT', 'C', 0, 0, $x + 12, $y, true);

                $pdf->MultiCell(2, 0.8, number_format($hasProduct->quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 15, $y, true);
                $pdf->MultiCell(2, 0.8, number_format($hasProduct->quantity * $hasProduct->product->weight, 2, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17, $y, true);

                $y += 0.8;

                if ($y >= 27) {
                    $pdf->addPage();
                    $y = 1;
                }
                $item_no++;

                $total_quantity += $hasProduct->quantity;
                $total_weight += $hasProduct->quantity * $hasProduct->product->weight;
            }
        }


        if ($y >= 27) {
            $pdf->addPage();
            $y = 1;
        }
        $pdf->SetFont("freesans", "B", 7);
        $pdf->MultiCell(15, 0.8, 'UKUPNO:', 'LRBT', 'C', 0, 0, $x, $y, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(2, 0.8, number_format($total_quantity, 0, ',', '.'), 'LRBT', 'R', 0, 0, $x + 15, $y, true, 0, false, true, 0.8, 'M', true);
        $pdf->MultiCell(2, 0.8, number_format($total_weight, 2, ',', '.'), 'LRBT', 'R', 0, 0, $x + 17, $y, true, 0, false, true, 0.8, 'M', true);

        $y += 1;
        $pdf->SetFont("freesans", "", 9);
        $pdf->MultiCell(19, 1.5, 'Broj europaleta: ' . count($this->activityPaletts), 0, 'C', 0, 0, $x, $y, true);
        $y += 1;
        $pdf->SetFont("freesans", "", 8);
        $pdf->MultiCell(4, 1.5, 'Robu primio', 'B', 'C', 0, 0, $x, $y, true);
        $pdf->MultiCell(4, 1.5, 'Robu izdao', 'B', 'C', 0, 0, $x + 15, $y, true);
        return $pdf;
    }

    public function createLabels($pdf)
    {
        $row = 1;
        $cnt = count($this->activityPaletts);
        foreach ($this->activityPaletts as $activity_palett) {
            $pdf->addPage();
            $pdf->SetMargins(1, 1, 1);
            $logo_path = Yii::getPathOfAlias("webroot") . '/themes/wtc3/img/logo.jpg';
            $pdf->Image($logo_path, 15, 1, 5, 1, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetFont("freesans", "B", 24);
            $pdf->MultiCell(19, 1, 'Broj naloga: ' . $this->order_number, 0, 'C', 0, 0, 1, 4, true);

            $pdf->SetFont("freesans", "B", 24);
            $pdf->MultiCell(19, 1, $this->customerSupplier->title, 0, 'C', 0, 0, 1, 5.5, true);

            $pdf->SetFont("freesans", "", 16);
            $pdf->MultiCell(19, 1, date('d.m.Y'), 0, 'C', 0, 0, 1, 7, true);

            $pdf->MultiCell(19, 1, 'Način dostave: ' . ($this->delivery_type != null ? $this->delivery_type : ' - '), 0, 'C', 0, 0, 1, 8, true);

            $pdf->SetFont("freesans", "B", 16);
            $pdf->MultiCell(19, 1, 'Paleta: ' . $row . '/' . $cnt, 0, 'C', 0, 0, 1, 9, true);

            $row++;

        }

        return $pdf;
    }

    public function getTotalWeight()
    {
        $total_weight = 0;
        foreach ($this->activityPaletts as $palett) {

            foreach ($palett->hasProducts as $hasProduct) {

                $total_weight += $hasProduct->quantity * $hasProduct->product->weight;

            }
        }

        return $total_weight;
    }

    public static function deliveryTypes()
    {
        return array(
            'POŠTA' => 'POŠTA',
            'Lično preuzimanje' => 'Lično preuzimanje',
            'Lična dostava' => 'Lična dostava',
            'BEX Express' => 'BEX Express',
            'Post Export' => 'Post Export',
            'OTKUP' => 'OTKUP',
            'D-Express' => 'D-Express',
            'Isporuka saradniku D-Express' => 'Isporuka saradniku D-Express',
            'D-Express na račun primaoca' => 'D-Express na račun primaoca',
            'Brza pošta po nalogu kupca' => 'Brza pošta po nalogu kupca',
            'Kancelarija' => 'Kancelarija',
            'D-otkup' => 'D-otkup',
            'Isporuka saradniku - lično preuzimanje' => 'Isporuka saradniku - lično preuzimanje',
            'Isporuka saradniku - lična dostava' => 'Isporuka saradniku - lična dostava',

        );
    }
}


