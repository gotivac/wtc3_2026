<?php

/**
 * This is the model class for table "auth_role_can".
 *
 * The followings are the available columns in table 'auth_role_can':
 * @property integer $id
 * @property integer $auth_role_id
 * @property integer $auth_controller_id
 * @property integer $auth_action_id
 * @property string $url_phrase
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 */
class AuthRoleCan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'auth_role_can';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auth_role_id, auth_controller_id, auth_action_id, url_phrase', 'required'),
			array('auth_role_id, auth_controller_id, auth_action_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('url_phrase', 'length', 'max'=>255),
			array('auth_action_id, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, auth_role_id, auth_controller_id, auth_action_id, url_phrase, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
            'action' => array(self::BELONGS_TO,'AuthAction','auth_action_id'),
            'role' => array(self::BELONGS_TO,'AuthRole','auth_role_id'),
            'controller' => array(self::BELONGS_TO,'AuthController','auth_controller_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'auth_role_id' => Yii::t('app','Auth Role'),
			'auth_controller_id' => Yii::t('app','Auth Controller'),
			'auth_action_id' => Yii::t('app','Auth Action'),
			'url_phrase' => Yii::t('app','Url Phrase'),
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
		$criteria->compare('auth_role_id',$this->auth_role_id);
		$criteria->compare('auth_controller_id',$this->auth_controller_id);
		$criteria->compare('auth_action_id',$this->auth_action_id);
		$criteria->compare('url_phrase',$this->url_phrase,true);
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
	 * @return AuthRoleCan the static model class
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

    public static function cant($auth_role_id) {
        $sql = "DELETE FROM auth_role_can WHERE auth_role_id=$auth_role_id";

        return Yii::app()->db->createCommand($sql)->execute();
    }
}
