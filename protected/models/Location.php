<?php

/**
 * This is the model class for table "location".
 *
 * The followings are the available columns in table 'location':
 * @property integer $id
 * @property string $title
 * @property string $address
 * @property string $email
 * @property integer $description
 * @property integer $inbound_palletes
 * @property integer $inbound_trucks
 * @property integer $outbound_palletes
 * @property integer $outbound_trucks

 * @property integer $system_acceptance
 * @property string $disabled_days
 * @property string $disabled_dates
 * @property integer $min_days
 * @property integer $max_days
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Section[] $sections
 * @property Worker[] $workers
 */
class Location extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'location';
	}



	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,min_days,max_days', 'required'),
			array('title', 'unique'),
			array('inbound_palletes, inbound_trucks, outbound_palletes, outbound_trucks, system_acceptance, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('title, email', 'length', 'max'=>255),
			array('address, description, disabled_dates, disabled_days, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, address, email, description, inbound_palletes, inbound_trucks, outbound_palletes, outbound_trucks, system_acceptance, min_days, max_days, disabled_dates, disabled_days, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'sections' => array(self::HAS_MANY, 'Section', 'location_id'),
			'workers' => array(self::HAS_MANY, 'Worker', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'title' => Yii::t('app','Title'),
			'address' => Yii::t('app','Address'),
			'email' => Yii::t('app','Email'),
			'description' => Yii::t('app','Description'),
            'inbound_palletes' => Yii::t('app', 'Capacity Inbound Palletes'),
            'inbound_trucks' => Yii::t('app', 'Capacity Inbound Trucks'),
            'outbound_palletes' => Yii::t('app', 'Capacity Outbound Palletes'),
            'outbound_trucks' => Yii::t('app', 'Capacity Outbound Trucks'),
            'system_acceptance' => Yii::t('app', 'Capacity System Acceptance'),
            'min_days' => Yii::t('app','Earliest'),
            'max_days' => Yii::t('app','Latest'),
            'disabled_dates'=>Yii::t('app','Disabled Dates'),
            'disabled_days'=>Yii::t('app','Disabled Days'),
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('description',$this->description);
        $criteria->compare('inbound_palletes', $this->inbound_palletes);
        $criteria->compare('inbound_trucks', $this->inbound_trucks);
        $criteria->compare('outbound_palletes', $this->outbound_palletes);
        $criteria->compare('outbound_trucks', $this->outbound_trucks);
        $criteria->compare('system_acceptance', $this->system_acceptance);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Location the static model class
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

        $disabled_days = array();
        $disabled_dates = array();

        if (is_array($this->disabled_days)) {
            foreach ($this->disabled_days as $k=>$v) {
                array_push($disabled_days,$k);
            }
        }

        if (is_array($this->disabled_dates)) {
            foreach ($this->disabled_dates as $date) {
                array_push($disabled_dates,date('Y-m-d',strtotime($date)));
            }
        }

        $this->disabled_dates = json_encode($disabled_dates);
        $this->disabled_days = json_encode($disabled_days);
		return parent::beforeSave();
	}

    public function afterFind() {

        $disabled_days = json_decode($this->disabled_days,true);
        $disabled_dates = json_decode($this->disabled_dates,true);
        $this->disabled_days = is_array($disabled_days) ? $disabled_days : array();
        $this->disabled_dates = is_array($disabled_dates) ? $disabled_dates : array();

        return parent::afterFind();

    }

    public function afterSave() {
        $disabled_days = json_decode($this->disabled_days,true);
        $disabled_dates = json_decode($this->disabled_dates,true);
        $this->disabled_days = is_array($disabled_days) ? $disabled_days : array();
        $this->disabled_dates = is_array($disabled_dates) ? $disabled_dates : array();
        return parent::afterSave();

    }


    public function getSurface()
    {
       $surface = 0;
       foreach($this->sections as $section) {
           $surface += $section->surface;
       }
       return $surface;
    }

    public function getClientIds()
    {
        $sql = 'SELECT id FROM client WHERE location_id = ' . $this->id;
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }

    public function byUser()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($user->location) {
            return $this->findAllByAttributes(array('id' => $user->location->id));
        }
        return $this->findAll();
    }
}
