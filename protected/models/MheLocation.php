<?php

/**
 * This is the model class for table "mhe_location".
 *
 * The followings are the available columns in table 'mhe_location':
 * @property integer $id
 * @property integer $mhe_type_id
 * @property integer $location_id
 * @property string $title
 * @property string $code
 * @property string $serial_number
 * @property string $description
 * @property string $failure_email
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Location $location
 * @property MheType $mheType
 */
class MheLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mhe_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('title,code,serial_number,failure_email','required'),
			array('mhe_type_id, location_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max'=>45),
			array('serial_number, failure_email', 'length', 'max'=>255),
			array('description, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mhe_type_id, location_id, title, code, serial_number, description, failure_email, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
			'mheType' => array(self::BELONGS_TO, 'MheType', 'mhe_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'mhe_type_id' => Yii::t('app','Mhe Type'),
			'location_id' => Yii::t('app','Location'),
			'title' => Yii::t('app','Title'),
			'code' => Yii::t('app','Code'),
			'serial_number' => Yii::t('app','Serial Number'),
			'description' => Yii::t('app','Description'),
			'failure_email' => Yii::t('app','Failure Email'),
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
		$criteria->compare('mhe_type_id',$this->mhe_type_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('failure_email',$this->failure_email,true);
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
	 * @return MheLocation the static model class
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
}
