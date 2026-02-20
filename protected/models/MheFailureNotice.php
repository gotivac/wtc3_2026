<?php

/**
 * This is the model class for table "mhe_failure_notice".
 *
 * The followings are the available columns in table 'mhe_failure_notice':
 * @property integer $id
 * @property integer $mhe_location_id
 * @property string $description
 * @property integer $operates
 * @property string $notice_datetime
 * @property string $solution_datetime
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property MheLocation $mheLocation
 */
class MheFailureNotice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mhe_failure_notice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mhe_location_id, description, operates, notice_datetime', 'required'),
			array('mhe_location_id, operates, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('description, solution_datetime, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mhe_location_id, description, operates, notice_datetime, solution_datetime, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'mheLocation' => array(self::BELONGS_TO, 'MheLocation', 'mhe_location_id'),
            'user' => array(self::BELONGS_TO,'User','created_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'mhe_location_id' => Yii::t('app','Mhe Location'),
			'description' => Yii::t('app','Description'),
			'operates' => Yii::t('app','Operates'),
			'notice_datetime' => Yii::t('app','Notice Datetime'),
			'solution_datetime' => Yii::t('app','Solution Datetime'),
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
		$criteria->compare('mhe_location_id',$this->mhe_location_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('operates',$this->operates);
		$criteria->compare('DATE_FORMAT(notice_datetime, "%d.%m.%Y %H:%i")',$this->notice_datetime,true);
		$criteria->compare('DATE_FORMAT(solution_datetime, "%d.%m.%Y %H:%i")',$this->solution_datetime,true);
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
	 * @return MheFailureNotice the static model class
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
        $this->notice_datetime = $this->notice_datetime != '' ? $this->notice_datetime = date('Y-m-d H:i:s', strtotime($this->notice_datetime)) : null;
        $this->solution_datetime = $this->solution_datetime != '' ? $this->solution_datetime = date('Y-m-d H:i:s', strtotime($this->solution_datetime)) : null;


		return parent::beforeSave();
	}

    public function afterFind()
    {
        $this->notice_datetime = $this->notice_datetime != null ? $this->notice_datetime = date('d.m.Y H:i', strtotime($this->notice_datetime)) : null;
        $this->solution_datetime = $this->solution_datetime != null ? $this->solution_datetime = date('d.m.Y H:i', strtotime($this->solution_datetime)) : null;
        return parent::afterFind();
    }
    public function afterSave()
    {
        $this->notice_datetime = $this->notice_datetime != null ? $this->notice_datetime = date('d.m.Y H:i', strtotime($this->notice_datetime)) : null;
        $this->solution_datetime = $this->solution_datetime != null ? $this->solution_datetime = date('d.m.Y H:i', strtotime($this->solution_datetime)) : null;
        return parent::afterSave();
    }
}
