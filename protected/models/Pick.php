<?php

/**
 * This is the model class for table "pick".
 *
 * The followings are the available columns in table 'pick':
 * @property integer $id
 * @property integer $activity_order_id
 * @property integer $sloc_id
 * @property string $sloc_code
 * @property integer $activity_palett_id
 * @property string $sscc_source
 * @property string $sscc_destination
 * @property integer $product_id
 * @property string $product_barcode
 * @property integer $target
 * @property integer $quantity
 * @property integer $packages
 * @property integer $units
 * @property string $pick_type
 * @property integer $status
 * @property integer $load_group
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ActivityOrder $activityOrder
 * @property ActivityPalett $activityPalett
 * @property Product $product
 * @property Sloc $sloc
 */
class Pick extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Pick the static model class
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
        return 'pick';
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
            // array('activity_order_id, sloc_id, sloc_code, activity_palett_id, sscc_source, product_id, product_barcode, target, quantity, packages, units, pick_type', 'required'),
            array('sloc_id, sloc_code,  pick_type', 'required'),
            array('activity_order_id, sloc_id, activity_palett_id, product_id, target, quantity, packages, units, status, load_group, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('sloc_code, sscc_source, sscc_destination, product_barcode, pick_type', 'length', 'max' => 255),
            array('created_dt, updated_dt', 'safe'),
            array('quantity', 'numerical', 'integerOnly'=>true, 'min'=>0),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('activity_id, id, activity_order_id, sloc_id, sloc_code, activity_palett_id, sscc_source, sscc_destination, product_id, product_barcode, target, quantity, packages, units, pick_type, status, load_group, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'activityPalett' => array(self::BELONGS_TO, 'ActivityPalett', 'activity_palett_id'),
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'sloc' => array(self::BELONGS_TO, 'Sloc', 'sloc_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'activity_order_id' => Yii::t('app', 'Activity Order'),
            'sloc_id' => Yii::t('app', 'Sloc'),
            'sloc_code' => Yii::t('app', 'SLOC'),
            'activity_palett_id' => Yii::t('app', 'Activity Palett'),
            'sscc_source' => Yii::t('app', 'SSCC'),
            'sscc_destination' => Yii::t('app', 'Na paletu SSCC'),
            'product_id' => Yii::t('app', 'Product'),
            'product_barcode' => Yii::t('app', 'Product'),
            'target' => Yii::t('app','Target'),
            'quantity' => Yii::t('app', 'Quantity'),
            'packages' => Yii::t('app', 'PAK'),
            'units' => Yii::t('app', 'KOM'),
            'pick_type' => Yii::t('app', 'Pick Type'),
            'status' => Yii::t('app', 'Status'),
            'load_group' => Yii::t('app','Load Group'),
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
        $criteria->compare('activity_order_id', $this->activity_order_id);
        $criteria->compare('sloc_id', $this->sloc_id);
        $criteria->compare('sloc_code', $this->sloc_code, true);
        $criteria->compare('activity_palett_id', $this->activity_palett_id);
        $criteria->compare('sscc_source', $this->sscc_source, true);
        $criteria->compare('sscc_destination', $this->sscc_destination, true);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('product_barcode', $this->product_barcode, true);
        $criteria->compare('target', $this->target);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('packages', $this->units);
        $criteria->compare('pick_type', $this->pick_type, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('load_group', $this->load_group);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 999)
        ));
    }

    public function activitySearch()
    {
        $criteria = new CDbCriteria;

        if ($this->activity_id) {

            $activity_orders = ActivityOrder::model()->findAllByAttributes(array('activity_id' =>$this->activity_id));

            $activity_order_ids = array();
            foreach ($activity_orders as $activity_order) {
                $activity_order_ids[] = $activity_order->id;
            }

            if (!empty($activity_order_ids)) {
                $criteria->addInCondition('activity_order_id',$activity_order_ids);
            } else {
                $criteria->compare('id', 0);
            }

        } else {
            $criteria->compare('id', 0);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 999)
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

        $this->sscc_source = $this->sscc_source != null ? trim($this->sscc_source) : null;
        $this->sscc_destination = $this->sscc_destination != null ? trim($this->sscc_destination) : null;
        $this->product_barcode = $this->product_barcode != null ? trim($this->product_barcode) : null;

        return parent::beforeSave();
    }



    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function afterSave()
    {
        if ($this->sscc_destination != NULL && $this->status == 0) {
            if ($this->pick_type == 'palett') {

                $source_palett = ActivityPalett::model()->findByPk($this->activity_palett_id);
                $activity_order = ActivityOrder::model()->findByPk($this->activity_order_id);
                $activity_palett = new ActivityPalett;

                $activity_palett->attributes = array(
                    'activity_id' => $activity_order->activity->id,
                    'activity_order_id' => $this->activity_order_id,
                    'sscc' => $this->sscc_destination,
                );

                if ($activity_palett->save()) {

                    foreach ($source_palett->hasProducts as $has_product) {
                        $activity_palett_has_product = new ActivityPalettHasProduct;
                        $activity_palett_has_product->attributes = array(
                            'activity_palett_id' => $activity_palett->id,
                            'sscc' => $has_product->sscc,
                            'product_id' => $has_product->product_id,
                            'product_barcode' => $has_product->product_barcode,
                            'quantity' => $this->quantity,
                            'packages' => $this->packages,
                            'units' => $this->units,
                            'expire_date' => $has_product->expire_date,
                            'batch' => $has_product->batch,
                            'pick_id' => $this->id,
                        );

                        $activity_palett_has_product->save();
                    }
                }
                //    $this->activity_palett_id = $activity_palett->id;
            } else if ($this->pick_type == 'product') {

                $has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id'=>$this->activity_palett_id,'product_id'=>$this->product_id));
                $destination_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id'=>$this->activityOrder->id,'sscc'=>$this->sscc_destination));

                if ($destination_palett) {


                        $activity_palett_has_product = new ActivityPalettHasProduct;
                        $activity_palett_has_product->attributes = array(
                            'activity_palett_id' => $destination_palett->id,
                            'sscc' => $destination_palett->sscc,
                            'product_id' => $this->product_id,
                            'product_barcode' => $this->product_barcode,
                            'quantity' => $this->quantity,
                            'packages' => $this->packages,
                            'units' => $this->units,
                            'expire_date' => $has_product ? $has_product->expire_date : null,
                            'batch' => $has_product ? $has_product->batch : null,
                            'pick_id' => $this->id,
                        );

                        $activity_palett_has_product->save();

                }
            } else if ($this->pick_type == 'move' && $this->product_id != null) {
                $has_product = ActivityPalettHasProduct::model()->findByAttributes(array('activity_palett_id'=>$this->activity_palett_id,'product_id'=>$this->product_id));
                $destination_palett = ActivityPalett::model()->findByAttributes(array('activity_order_id'=>$this->activityOrder->id,'sscc'=>$this->sscc_destination));

                if ($has_product && $destination_palett) {

                    $activity_palett_has_product = new ActivityPalettHasProduct;
                    $activity_palett_has_product->attributes = array(
                        'activity_palett_id' => $destination_palett->id,
                        'sscc' => $destination_palett->sscc,
                        'product_id' => $this->product_id,
                        'product_barcode' => $this->product_barcode,
                        'quantity' => $this->quantity,
                        'packages' => $this->packages,
                        'units' => $this->units,
                        'expire_date' => $has_product->expire_date,
                        'batch' => $has_product->batch,
                        'pick_id' => $this->id,
                    );

                    $activity_palett_has_product->save();

                }
            }
        }

        Yii::app()->Helpers->saveLog($this);

        return parent::afterSave();
    }



    public function beforeValidate()
    {


        return parent::beforeValidate();
    }

    public static function snakeSorting($picking_list)
    {

        if (empty($picking_list)) {
            return array();
        }
        $sloc_code = array_column($picking_list, 'sloc_code');

        array_multisort($sloc_code, SORT_ASC, $picking_list);

        $old_street = (int)substr($picking_list[0]['sloc_code'], 1, 2);
        $sorted_picking_list = array();
        $picking_list_slice = array();

        foreach ($picking_list as $item) {

            $street = (int)substr($item['sloc_code'], 1, 2);

            if ($street != $old_street) {

                $sloc_code = array_column($picking_list_slice, 'sloc_code');


                /**   This part of algoritam works only for case of Simanovci where snake restarts at 10th street
                 *    This problem can be fixed by creating separate table for street order sorting
                 */

                if ($old_street % 2 == 0 && $old_street < 10) {
                    array_multisort($sloc_code, SORT_DESC, $picking_list_slice);
                } else if ($old_street % 2 == 0 && $old_street >= 10) {
                    array_multisort($sloc_code, SORT_ASC, $picking_list_slice);
                } else if ($old_street % 2 != 0 && $old_street < 10) {
                    array_multisort($sloc_code, SORT_ASC, $picking_list_slice);
                } else {
                    array_multisort($sloc_code, SORT_DESC, $picking_list_slice);
                }

                /** END OF ALGORITAM */


                $sorted_picking_list = array_merge($sorted_picking_list, $picking_list_slice);
                $picking_list_slice = array($item);
                $old_street = $street;
            } else {

                $picking_list_slice[] = $item;
            }

        }
        // echo '<pre>';var_dump($picking_list);
        $picking_list = array_merge($sorted_picking_list, $picking_list_slice);

        return $picking_list;
    }
}
