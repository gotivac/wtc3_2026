<?php

/**
 * This is the model class for table "gate".
 *
 * The followings are the available columns in table 'gate':
 * @property integer $id
 * @property integer $location_id
 * @property integer $gate_type_id
 * @property string $title
 * @property string $code
 * @property integer $tms_gate
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property GateType $gateType
 * @property Section $section
 */
class Gate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('location_id, gate_type_id, tms_gate, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max'=>255),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, location_id, gate_type_id, title, code, tms_gate, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'gateType' => array(self::BELONGS_TO, 'GateType', 'gate_type_id'),
			'sections' => array(self::MANY_MANY, 'Section', 'gate_has_section(gate_id,section_id)'),
			'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),

			'location_id' => Yii::t('app','Location'),
			'gate_type_id' => Yii::t('app','Gate Type'),
			'title' => Yii::t('app','Title'),
			'code' => Yii::t('app','Code'),
			'tms_gate' => Yii::t('app','TMS Gate'),
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

		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('gate_type_id',$this->gate_type_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('tms_gate',$this->tms_gate);
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
	 * @return Gate the static model class
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
        if ($this->sections && $this->sections[0]->location) {
            $this->location_id = $this->sections[0]->location->id;
        }
		return parent::beforeSave();
	}

    public function byLocation($location_id = false)
    {
        if ($location_id) {
            return $this->findAllByAttributes(array('location_id' => $location_id));
        }

        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($user->location) {
            return $this->findAllByAttributes(array('location_id' => $user->location->id));
        }
        return $this->findAll();
    }
}
