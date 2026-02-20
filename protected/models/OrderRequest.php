<?php

/**
 * This is the model class for table "order_request".
 *
 * The followings are the available columns in table 'order_request':
 * @property integer $id
 * @property integer $urgent
 * @property integer $activity_type_id
 * @property string $direction
 * @property integer $location_id
 * @property string $load_list
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property OrderClient[] $orderClients
 */
class OrderRequest extends CActiveRecord
{
    public $order_number;

    public $delivery_type_search = false;

    public $status_filter = false;

    public $isExcel = false;

    public $finished_from = false;
    public $finished_to = false;

    public $delivered_from = false;
    public $delivered_to = false;


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OrderRequest the static model class
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
        return 'order_request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_type_id, location_id', 'required'),
            array('urgent, activity_type_id, location_id, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('direction, load_list', 'length', 'max' => 255),
            array('order_number, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, delivery_type_search, finished_from, finished_to, delivered_from, delivered_to, isExcel, status_filter, order_number, urgent, activity_type_id, direction, location_id, load_list, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'orderClients' => array(self::HAS_MANY, 'OrderClient', 'order_request_id'),
            'activityType' => array(self::BELONGS_TO, 'ActivityType', 'activity_type_id'),
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'timeSlot' => array(self::HAS_ONE, 'TimeSlot', 'order_request_id'),
            'activity' => array(self::HAS_ONE, 'Activity', 'order_request_id'),
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
            'direction' => Yii::t('app', 'Direction'),
            'location_id' => Yii::t('app', 'Location'),
            'delivery_type_search' => Yii::t('app', 'Delivery Type'),
            'order_number' => Yii::t('app', 'Order Number'),
            'load_list' => Yii::t('app', 'Load List'),
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

        if ($this->order_number != null || $this->delivery_type_search != false || $this->finished_from != false || $this->finished_to != false || $this->delivered_from != false || $this->delivered_to != false) {

            if ($this->order_number != null) {
                $sql = 'SELECT order_request_id FROM order_client WHERE order_number LIKE "%' . $this->order_number . '%"';
                $order_request_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $order_request_ids = false;
            }

            if ($this->delivery_type_search != false) {
                $sql = 'SELECT order_request_id FROM order_client WHERE delivery_type LIKE "%' . $this->delivery_type_search . '%"';
                $order_request_delivery_type_ids = Yii::app()->db->createCommand($sql)->queryColumn();

            } else {
                $order_request_delivery_type_ids = false;
            }

            if ($this->finished_from != false) {
                $sql = 'SELECT order_request_id FROM activity WHERE system_acceptance_datetime >=  "' . date("Y-m-d H:i:s", strtotime($this->finished_from)) . '"';
                $order_request_finished_from_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $order_request_finished_from_ids = false;
            }
            if ($this->finished_to != false) {
                $sql = 'SELECT order_request_id FROM activity WHERE system_acceptance_datetime <=  "' . date("Y-m-d H:i:s", strtotime($this->finished_to)) . '"';
                $order_request_finished_to_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $order_request_finished_to_ids = false;
            }

            if ($this->delivered_from != false) {
                $sql = 'SELECT order_request_id FROM activity WHERE truck_dispatch_datetime >=  "' . date("Y-m-d H:i:s", strtotime($this->delivered_from)) . '"';

                $order_request_delivered_from_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $order_request_delivered_from_ids = false;
            }
            if ($this->delivered_to != false) {
                $sql = 'SELECT order_request_id FROM activity WHERE truck_dispatch_datetime <=  "' . date("Y-m-d H:i:s", strtotime($this->delivered_to)) . '"';
                $order_request_delivered_to_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $order_request_delivered_to_ids = false;
            }


            if ((empty($order_request_ids) && $order_request_ids !== false) ||
                (empty($order_request_delivery_type_ids) && $order_request_delivery_type_ids !== false) ||
                (empty($order_request_finished_from_ids) && $order_request_finished_from_ids !== false) ||
                (empty($order_request_finished_to_ids) && $order_request_finished_to_ids !== false) ||
                (empty($order_request_delivered_from_ids) && $order_request_delivered_from_ids !== false) ||
                (empty($order_request_delivered_to_ids) && $order_request_delivered_to_ids !== false)
            ) {

                $array = false;
            } else {
                $sum_array = array();
                if ($order_request_ids !== false) {
                    $sum_array[] = $order_request_ids;
                }
                if ($order_request_delivery_type_ids !== false) {
                    $sum_array[] = $order_request_delivery_type_ids;
                }

                if ($order_request_finished_from_ids !== false) {
                    $sum_array[] = $order_request_finished_from_ids;
                }

                if ($order_request_finished_to_ids !== false) {
                    $sum_array[] = $order_request_finished_to_ids;
                }

                if ($order_request_delivered_from_ids !== false) {
                    $sum_array[] = $order_request_delivered_from_ids;
                }

                if ($order_request_delivered_to_ids !== false) {
                    $sum_array[] = $order_request_delivered_to_ids;
                }


                $array = array();
                $first = true;
                foreach ($sum_array as $a) {
                    if (empty($array) && $first) {
                        $first = false;
                        $array = $a;
                    } else {
                        $array = array_intersect($array, $a);
                    }
                }


            }
            /*
                        if ($order_request_ids && $order_request_delivery_type_ids) {
                            $array = array_intersect($order_request_ids, $order_request_delivery_type_ids);
                        } else if ($order_request_ids) {
                            $array = $order_request_ids;
                        } else if ($order_request_delivery_type_ids) {
                            $array = $order_request_delivery_type_ids;
                        } else {
                            $array = false;
                        }

                        if ($array) {

                            if ($this->finished_from != false) {
                                $sql = 'SELECT order_request_id FROM activity WHERE system_acceptance_datetime >=  "' . date("Y-m-d H:i:s",strtotime($this->finished_from)) .  '"';
                                $order_request_finished_from_ids = Yii::app()->db->createCommand($sql)->queryColumn();
                            } else {
                                $order_request_finished_from_ids = false;
                            }

                            if ($order_request_finished_from_ids) {
                                $array = array_intersect($array,$order_request_finished_from_ids);
                            } else {
                                $array = false;
                            }

                        }
            */


            if ($array) {
                $criteria->addInCondition('id', $array);
            } else {
                $criteria->compare('id', 0);
            }
        }


        if ($this->status_filter) {
            if ($this->status_filter == 'yellow') {
                $sql = "SELECT id FROM order_request WHERE id NOT IN (SELECT order_request_id FROM activity)";
                $order_request_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else if ($this->status_filter == 'green') {
                $sql = "SELECT id FROM order_request WHERE id IN (SELECT order_request_id FROM activity WHERE system_acceptance_datetime IS NULL AND truck_dispatch_datetime IS NULL)";
                $order_request_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else if ($this->status_filter == 'red') {
                $sql = "SELECT id FROM order_request WHERE id IN (SELECT order_request_id FROM activity WHERE system_acceptance_datetime IS NOT NULL AND truck_dispatch_datetime IS NULL)";
                $order_request_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            } else {
                $sql = "SELECT id FROM order_request";
                $order_request_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            }


            $criteria->addInCondition('id', $order_request_ids);


        }


        // $criteria->compare('id', $this->id);
        $criteria->compare('urgent', $this->urgent);
        $criteria->compare('activity_type_id', $this->activity_type_id);
        $criteria->compare('direction', $this->direction, true);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('load_list', $this->load_list, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('DATE_FORMAT(created_dt,"%d.%m.%Y")', $this->created_dt);


        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => $this->isExcel ? $this->isExcel : 200),
            'sort' => array('defaultOrder' => 'id DESC')
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

            if ($this->timeSlot) {
                $this->timeSlot->order_request_id = NULL;
                $this->timeSlot->save();
            }

        }

        Yii::app()->Helpers->saveLog($this);
        $activity_type = ActivityType::model()->findByPk($this->activity_type_id);
        if ($activity_type) {
            $this->direction = $activity_type->direction;
        }


        return parent::beforeSave();
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function getTotalPaletts()
    {
        $sum = 0;
        foreach ($this->orderClients as $order_client) {

            $sum += $order_client->totalPaletts;

        }
        return $sum;

    }

    public function getOrderNumber()
    {
        $order_number = array();
        foreach ($this->orderClients as $order_client) {
            $order_number[] = $order_client->order_number;
        }
        if (count($order_number) > 1) {
            return $order_number;
        } else if (count($order_number) == 1) {
            return $order_number[0];
        }
        return false;
    }

    public function behaviors()
    {

        return array(
            'MySearch' => array(
                'class' => 'application.components.MySearch',
            ),
        );
    }

    public function getDeadline()
    {
        $disabled_days = $this->location->disabled_days;
        $disabled_dates = $this->location->disabled_dates;

        $deadline_date = date('Y-m-d', strtotime($this->created_dt));
        $deadline_day = date('w', strtotime($deadline_date));

        if ($this->isCreatedAfterWorkHours()) {
            $deadline_date = date('Y-m-d', strtotime($deadline_date . ' +1 day'));
            $deadline_day = date('w', strtotime($deadline_date));
        }
        $i=3;

        do {
            if (!in_array($deadline_day,$disabled_days) && !in_array($deadline_date,$disabled_dates)) {
                $final_date = $deadline_date;
                $i--;
            }
            $deadline_date = date('Y-m-d', strtotime($deadline_date . ' +1 day'));
            $deadline_day = date('w', strtotime($deadline_date));
        } while ($i > 0);


        return $final_date;
    }

    public function isCreatedAfterWorkHours()
    {
        $deadline_date = date('Y-m-d', strtotime($this->created_dt));
        if (strtotime($this->created_dt) > strtotime($deadline_date . ' 15:00:00')) {
            return true;
        }
        return false;
    }

    public function getTotalRows()
    {
        $result = 0;

        foreach ($this->orderClients as $order_client) {

                $result += count($order_client->orderProducts);

        }
        return $result;

    }
}
