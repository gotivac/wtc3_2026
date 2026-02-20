<?php

/**
 * This is the model class for table "mhe_activity".
 *
 * The followings are the available columns in table 'mhe_activity':
 * @property integer $id
 * @property integer $mhe_activity_type_id
 * @property integer $mhe_location_id
 * @property string $date_and_time
 * @property string $notes
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property MheActivityType $mheActivityType
 * @property MheLocation $mheLocation
 */
class MheActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mhe_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mhe_activity_type_id, mhe_location_id, date_and_time', 'required'),
			array('mhe_activity_type_id, mhe_location_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('notes, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mhe_activity_type_id, mhe_location_id, date_and_time, notes, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'mheActivityType' => array(self::BELONGS_TO, 'MheActivityType', 'mhe_activity_type_id'),
			'mheLocation' => array(self::BELONGS_TO, 'MheLocation', 'mhe_location_id'),
            'user' => array(self::BELONGS_TO,'User','created_user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'mhe_activity_type_id' => Yii::t('app','Mhe Activity Type'),
			'mhe_location_id' => Yii::t('app','Mhe Location'),
			'date_and_time' => Yii::t('app','Date And Time'),
			'notes' => Yii::t('app','Notes'),
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
		$criteria->compare('mhe_activity_type_id',$this->mhe_activity_type_id);
		$criteria->compare('mhe_location_id',$this->mhe_location_id);
        $criteria->compare('DATE_FORMAT(date_and_time, "%d.%m.%Y %H:%i")',$this->date_and_time,true);
		$criteria->compare('notes',$this->notes,true);
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
	 * @return MheActivity the static model class
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
        $this->date_and_time = $this->date_and_time != '' ? $this->date_and_time = date('Y-m-d H:i:s', strtotime($this->date_and_time)) : null;
		return parent::beforeSave();
	}

    public function afterFind()
    {
        $this->date_and_time = $this->date_and_time != null ? $this->date_and_time = date('d.m.Y H:i', strtotime($this->date_and_time)) : null;

        return parent::afterFind();
    }
    public function afterSave()
    {
        $this->date_and_time = $this->date_and_time != null ? $this->date_and_time = date('d.m.Y H:i', strtotime($this->date_and_time)) : null;

        return parent::afterSave();
    }
}
