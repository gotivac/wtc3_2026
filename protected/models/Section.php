<?php

/**
 * This is the model class for table "section".
 *
 * The followings are the available columns in table 'section':
 * @property integer $id
 * @property integer $location_id
 * @property string $title
 * @property string $code
 * @property string $surface
 * @property string $tsm_start_time
 * @property string $tsm_end_time
 * @property integer $wtc_managed
 * @property integer $is_customs
 * @property string $customs_warehouse_number
 * @property string $customs_office_code
 * @property string $customs_warehouse_type
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Gate[] $gates
 * @property Location $location
 */
class Section extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Section the static model class
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
        return 'section';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('location_id, title, tsm_start_time, tsm_end_time', 'required'),
            array('location_id, wtc_managed, is_customs, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('title, code, customs_warehouse_number, customs_warehouse_type, customs_office_code', 'length', 'max' => 255),
            array('surface', 'length', 'max' => 10),
            array('created_dt, updated_dt', 'safe'),
            array('customs_warehouse_number', 'unique'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, location_id, title, code, surface, wtc_managed, is_customs, customs_warehouse_number, customs_warehouse_type, customs_office_code, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'hasGates' => array(self::HAS_MANY, 'GateHasSection', 'section_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'location_id' => Yii::t('app', 'Location'),
            'title' => Yii::t('app', 'Title'),
            'code' => Yii::t('app', 'Code'),
            'surface' => Yii::t('app', 'Surface'),
            'tsm_start_time' => Yii::t('app', 'TSM Start Time'),
            'tsm_end_time' => Yii::t('app', 'TSM End Time'),

            'wtc_managed' => Yii::t('app', 'Wtc Managed'),
            'is_customs' => Yii::t('app', 'Is Customs'),
            'customs_warehouse_number' => Yii::t('app', 'Customs Warehouse Number'),
            'customs_warehouse_type' => Yii::t('app', 'Customs Warehouse Type'),
            'customs_office_code' => Yii::t('app', 'Customs Office Code'),
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
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('surface', $this->surface, true);

        $criteria->compare('wtc_managed', $this->wtc_managed);
        $criteria->compare('is_customs', $this->is_customs);
        $criteria->compare('customs_warehouse_number', $this->customs_warehouse_number, true);
        $criteria->compare('customs_warehouse_type', $this->customs_warehouse_type, true);
        $criteria->compare('customs_office_code', $this->customs_office_code, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'location_id ASC'
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
        return parent::beforeSave();
    }

    public function getGatesIds()
    {
        $ids = array();
        foreach ($this->hasGates as $has_gate) {
            $ids[] = $has_gate->gate_id;
        }
        return $ids;
    }

    public function getActiveOutboundActivityOrders($location_id)
    {
        $condition = 'location_id='.$location_id;

        if (count($this->gatesIds) > 0) {
            $condition .= ' AND gate_id IN (' . implode(',',$this->getGatesIds()) . ')';
        }

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
}
