<?php

/**
 * This is the model class for table "time_slot_details".
 *
 * The followings are the available columns in table 'time_slot_details':
 * @property integer $id
 * @property integer $time_slot_id
 * @property integer $client_id
 * @property integer $paletts
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Client $client
 * @property TimeSlot $timeSlot
 */
class TimeSlotDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'time_slot_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id,paletts', 'required'),
			array('order_client_id, time_slot_id, client_id, paletts, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_client_id, time_slot_id, client_id, paletts, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
			'timeSlot' => array(self::BELONGS_TO, 'TimeSlot', 'time_slot_id'),
            'attachments' => array(self::HAS_MANY,'TimeSlotDetailsAttachment','time_slot_details_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'time_slot_id' => Yii::t('app','Time Slot'),
			'client_id' => Yii::t('app','Client'),
			'paletts' => Yii::t('app','Pallets'),
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
		$criteria->compare('time_slot_id',$this->time_slot_id);
		$criteria->compare('client_id',$this->client_id);
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TimeSlotDetail the static model class
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
		return parent::beforeSave();
	}



    /**
     *  This function returns array of Time Slot Ids that contains free space for specified client
     */

    public static function getFreeTimeSlotIds($client = false)
    {
        if ($client) {

            $sql ="SELECT time_slot_details_id FROM order_main  WHERE time_slot_details_id IS NOT NULL AND client_id=" . $client->id;
            $time_slot_details_ids = Yii::app()->db->createCommand($sql)->queryColumn();

            if (count($time_slot_details_ids) > 0) {

                $sql = "SELECT time_slot_id FROM " . self::tableName() . " WHERE client_id = ".$client->id." AND id NOT IN (".implode(",",$time_slot_details_ids).")";
            } else {
                $sql = "SELECT time_slot_id FROM " . self::tableName() . " WHERE client_id = ".$client->id;
            }

        } else {
            $sql = "SELECT time_slot_id FROM " . self::tableName() . " WHERE id NOT IN SELECT time_slot_details_id FROM order_main";
        }


        return Yii::app()->db->createCommand($sql)->queryColumn();

    }



}
