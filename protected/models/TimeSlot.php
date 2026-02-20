<?php

/**
 * This is the model class for table "time_slot".
 *
 * The followings are the available columns in table 'time_slot':
 * @property integer $id
 * @property string $order_request_id
 * @property integer $activity_type_id
 * @property integer $gate_id
 * @property integer $location_id
 * @property integer $section_id
 * @property string $defined_date
 * @property string $start_time
 * @property string $end_time
 * @property integer $truck_type_id
 * @property string $license_plate
 * @property string $notes
 * @property integer $urgent
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property TruckType $truckType
 * @property TimeSlotDetail[] $timeSlotDetails
 */
class TimeSlot extends CActiveRecord
{
    public $filtered = false;


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TimeSlot the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('truck_type_id, license_plate, activity_type_id', 'required'),
            array('order_request_id, gate_id, location_id, section_id, truck_type_id, activity_type_id, urgent, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('license_plate', 'length', 'max' => 255),
            array('uid, defined_date, start_time, end_time, notes, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, order_request_id, activity_type_id, section_id, urgent, gate_id, location_id, defined_date, start_time, end_time, truck_type_id, license_plate, notes, urgent, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'truckType' => array(self::BELONGS_TO, 'TruckType', 'truck_type_id'),
            'timeSlotDetails' => array(self::HAS_MANY, 'TimeSlotDetail', 'time_slot_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'gate' => array(self::BELONGS_TO, 'Gate', 'gate_id'),
            'section' => array(self::BELONGS_TO, 'Section', 'section_id'),
            'activityType' => array(self::BELONGS_TO, 'ActivityType', 'activity_type_id'),
            'order' => array(self::BELONGS_TO,'OrderRequest','order_request_id'),


        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'urgent' => Yii::t('app', 'Urgent'),
            'activity_type_id' => Yii::t('app', 'Activity Type'),
            'order_request_id' => Yii::t('app', 'Order Request'),
            'defined_date' => Yii::t('app', 'Defined Date'),
            'location_id' => Yii::t('app', 'Location'),
            'section_id' => Yii::t('app', 'Section'),
            'gate_id' => Yii::t('app', 'Gate'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'truck_type_id' => Yii::t('app', 'Truck Type'),
            'license_plate' => Yii::t('app', 'License Plate'),
            'notes' => Yii::t('app', 'Notes'),
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

        if ($this->filtered !== false) {
            if (!empty($this->filtered)) {
                $criteria->addInCondition("t.id", $this->filtered);
            } else {
                $criteria->compare('t.id', 0);
            }
        } else {
            $criteria->compare('t.id', $this->id);
        }


        $criteria->compare('activity_type_id', $this->activity_type_id);
        $criteria->compare('DATE_FORMAT(defined_date,"%d.%m.%Y")', $this->defined_date, true);
        $criteria->compare('start_time', $this->start_time, true);
        $criteria->compare('end_time', $this->end_time, true);
        $criteria->compare('truck_type_id', $this->truck_type_id);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('gate_id', $this->gate_id);
        $criteria->compare('license_plate', $this->license_plate, true);

        $criteria->with = array('timeSlotDetails');
        $criteria->addCondition('timeSlotDetails.time_slot_id IS NOT NULL');

        $criteria->together = true;


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array('defaultOrder'=>'defined_date DESC')
        ));
    }

    public function beforeSave()
    {



        $this->defined_date = $this->defined_date != '' ? date('Y-m-d', strtotime($this->defined_date)) : null;
        $this->start_time = $this->start_time == '' ? null : $this->start_time;
        $this->end_time = $this->start_time == null ? null : $this->end_time;
        $this->gate_id = $this->start_time == null ? null : $this->gate_id;

        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');
            if ($this->section_id == null || $this->location_id == null && $this->order_request_id) {
                $order = OrderRequest::model()->findByPk($this->order_request_id);
                if ($order && count($order->orderClients) > 0) {
                    $this->location_id = $order->orderClients[0]->client->location_id;
                    $this->section_id = $order->orderClients[0]->client->section_id;
                }
            }
        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
            if ($this->start_time != null) {
                return $this->validateTime();
            }

            if (!$this->gate && !$this->start_time && !$this->defined_date) {
                $this->order_request_id = null;
            }

        }

        return parent::beforeSave();
    }

    public function validateTime()
    {


        if ($this->start_time == '' || $this->end_time == '') {

            $this->addError('start_time', Yii::t('app', 'Start time cannot be blank.'));
            return false;

        } else {

            $this->defined_date = date('Y-m-d', strtotime($this->defined_date));

            $truck_type = TruckType::model()->findByPk($this->truck_type_id);

            foreach ($this->timeSlotDetails as $time_slot_detail) {

                if ($time_slot_detail->client->section) {
                    $gate_ids = Yii::app()->db->createCommand('SELECT gate_id FROM gate_has_section WHERE section_id=' . $time_slot_detail->client->section->id)->queryColumn();

                    if (!empty($gate_ids)) {
                        $gates = Gate::model()->findAll(array('condition' => 'id IN (' . implode(',', $gate_ids) . ') AND gate_type_id = ' . $truck_type->gate_type_id));
                    }
                }
            }


            foreach ($gates as $gate) {
                if ($e = TimeSlot::checkTerm($gate->id, $this->defined_date, $this->start_time, $this->end_time)) {
                    continue;
                } else {
                    $this->gate_id = $gate->id;
                }
            }
            if ($this->start_time == '' || $this->end_time == '0') {
                $this->addError('start_time', Yii::t('app', 'Start time cannot be blank.'));
                $this->defined_date = date('d.m.Y', strtotime($this->defined_date));
                return false;
            } else if ($this->gate_id == '' || $this->gate_id == null) {
                $this->addError('start_time', Yii::t('app', 'No free gates at this time.'));
                $this->defined_date = date('d.m.Y', strtotime($this->defined_date));
                return false;
            } else if (in_array(date('N', strtotime($this->defined_date)), $this->location->disabled_days)) {
                $this->addError('defined_date', Yii::t('app', 'Incorrect date value.'));
                $this->defined_date = date('d.m.Y', strtotime($this->defined_date));
                return false;
            } else if (in_array(date('Y-m-d', strtotime($this->defined_date)), $this->location->disabled_dates)) {
                $this->addError('defined_date', Yii::t('app', 'Incorrect date value.'));
                $this->defined_date = date('d.m.Y', strtotime($this->defined_date));
                return false;
            }

            return true;
        }
    }

    public static function checkTerm($gate_id, $start_date, $start, $end, $id = false)
    {
        $sql = "SELECT * from " . self::tableName() . " WHERE";
        if ($id) {
            $sql .= " id != $id AND";
        }
        $sql .= " gate_id = $gate_id AND defined_date = '$start_date' AND (start_time = '$start' OR end_time = '$end' OR (start_time > '$start' AND start_time < '$end') OR (end_time > '$start' AND end_time <= '$end') OR (start_time < '$start' AND end_time > '$end'))";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'time_slot';
    }

    public function afterSave()
    {
        $this->defined_date = $this->defined_date != null ? date('d.m.Y', strtotime($this->defined_date)) : null;

        return parent::afterSave();
    }

    public function afterFind()
    {

        $this->start_time = $this->start_time != null ? date("H:i", strtotime($this->start_time)) : null;
        $this->end_time = $this->end_time != null ? date("H:i", strtotime($this->end_time)) : null;
        $this->defined_date = $this->defined_date != null ? date('d.m.Y', strtotime($this->defined_date)) : null;
        return parent::afterFind();
    }

    /**
     *  Function that returns free terms for current TimeSlot
     */

    public function getTermEnd($start_time)
    {

        $unloading_minutes = 0;
        $gates = array();

        $truck_type = TruckType::model()->findByPk($this->truck_type_id);
        $truck_duration = $truck_type->parking_minutes + $truck_type->dispatch_minutes;

        $details = $this->timeSlotDetails;
        foreach ($details as $time_slot_detail) {

            if ($time_slot_detail->client->section) {
                $gate_ids = Yii::app()->db->createCommand('SELECT gate_id FROM gate_has_section WHERE section_id=' . $time_slot_detail->client->section->id)->queryColumn();

                if (!empty($gate_ids)) {
                    $gates = Gate::model()->findAll(array('condition' => 'id IN (' . implode(',', $gate_ids) . ') AND gate_type_id = ' . $truck_type->gate_type_id));
                }
            }

            $unloading_level = $time_slot_detail->client->unloadingLevel;

            if ($unloading_level) {
                $unloading_minutes += ceil(($unloading_level->seconds_per_palett * $time_slot_detail->paletts) / 60);
            }

        }
        $service_duration = ceil(($unloading_minutes + $truck_duration) / 5) * 5;

        return date('H:i', strtotime($start_time . ':00 +' . $service_duration . ' minutes'));
    }

    public function getFreeTerms()
    {
        $day = date('Y-m-d', strtotime($this->defined_date));
        $term_duration = TruckType::model()->term();
        $weekday = date('w', strtotime($day));
        $terms = array();

        $unloading_minutes = 0;
        $gates = array();

        $truck_type = TruckType::model()->findByPk($this->truck_type_id);

        $truck_duration = $truck_type->parking_minutes + $truck_type->dispatch_minutes;

        foreach ($this->timeSlotDetails as $time_slot_detail) {

            if ($time_slot_detail->client->section) {
                $gate_ids = Yii::app()->db->createCommand('SELECT gate_id FROM gate_has_section WHERE section_id=' . $time_slot_detail->client->section->id)->queryColumn();

                if (!empty($gate_ids)) {
                    $gates = Gate::model()->findAll(array('condition' => 'id IN (' . implode(',', $gate_ids) . ') AND gate_type_id = ' . $truck_type->gate_type_id));
                }
            }

            $unloading_level = $time_slot_detail->client->unloadingLevel;
            if ($unloading_level) {
                $unloading_minutes += ceil(($unloading_level->seconds_per_palett * $time_slot_detail->paletts) / 60);
            }

        }


        $service_duration = $unloading_minutes + $truck_duration;

        foreach ($gates as $gate) {
            $current = $this->section->tsm_start_time;
            $end_working_time = date('H:i:s', strtotime($this->section->tsm_end_time . ' -' . $service_duration . ' minutes'));

            while ($current <= $end_working_time) {

                /* samo ako je tekuci dan u pitanju - za sada nepotrebna opcija */
                if ($day == date('Y-m-d') and $current < date('H:i:s')) {

                    $current = date('H:i:s', strtotime($current . ' +' . $term_duration . ' minutes'));

                    continue;
                }
                /* kraj */

                $end_time = date('H:i:s', strtotime($current . ' +' . $service_duration . ' minutes'));

                if (!TimeSlot::checkTerm($gate->id, $day, $current, $end_time, $this->id)) {
                    $new_term = date('H:i', strtotime($current));
                    if (!in_array($new_term, $terms)) {
                        $terms[] = date('H:i', strtotime($current));
                    }
                }

                $current = date('H:i:s', strtotime($current . ' +' . $term_duration . ' minutes'));
            }
        }

        sort($terms);

        return $terms;
    }

    public function getTotalPaletts()
    {
        $sum = 0;
        foreach ($this->timeSlotDetails as $time_slot_detail) {
            $sum += $time_slot_detail->paletts;
        }
        return $sum;

    }

    public function hasActivity()
    {
        return Activity::model()->findByAttributes(array('time_slot_id' => $this->id));
    }
}
