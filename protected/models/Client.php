<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property integer $id
 * @property string $title
 * @property string $official_title
 * @property string $tax_number
 * @property string $domain
 * @property integer $location_id
 * @property integer $section_id
 * @property integer $unloading_level_id
 * @property string $pick_methods
 * @property string $client_identification
 * @property string $postal_code
 * @property string $city
 * @property string $address
 * @property string $country
 * @property string $contact_person
 * @property string $phone
 * @property string $company_number
 * @property integer $client_type_id
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ClientHasSupplier[] $clientHasSuppliers
 * @property ClientHasSupplier[] $clientHasSuppliers1
 * @property OrderMain[] $orderMains
 * @property Product[] $products
 * @property TimeSlotDetails[] $timeSlotDetails
 * @property UserHasClient[] $userHasClients
 */
class Client extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client';
	}

    public $canView = false;
    public $canCreate = false;
    public $canUpdate = false;
    public $canDelete = false;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, location_id, section_id', 'required'),
			array('location_id, section_id, unloading_level_id, client_type_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('title, tax_number, domain, client_identification, postal_code, city, address, country, contact_person, phone, company_number', 'length', 'max'=>255),
			array('pick_methods, official_title, created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, official_title, tax_number, domain, location_id, section_id, unloading_level_id, pick_methods, client_identification, postal_code, city, address, country, contact_person, phone, company_number, client_type_id, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
            'userHasClients' => array(self::HAS_MANY, 'UserHasClient', 'client_id'),
            'buyers' => array(self::HAS_MANY,'ClientHasSupplier','supplier_id'),
            'suppliers' => array(self::HAS_MANY,'ClientHasSupplier','client_id'),
            'section' => array(self::BELONGS_TO,'Section','section_id'),
            'location' => array(self::BELONGS_TO,'Location','location_id'),
            'unloadingLevel' => array(self::BELONGS_TO,'UnloadingLevel','unloading_level_id'),
            'hasStorageTypes' => array(self::HAS_MANY,'ClientHasStorageType','client_id'),
            'storageTypes' => array(self::MANY_MANY, 'StorageType', 'client_has_storage_type(client_id,storage_type_id)'),
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
			'official_title' => Yii::t('app','Official Title'),
			'tax_number' => Yii::t('app','Tax Number'),
			'domain' => Yii::t('app','Domain'),
			'location_id' => Yii::t('app','Location'),
			'section_id' => 'Default ' . Yii::t('app','Section'),
			'unloading_level_id' => Yii::t('app','Unloading Level'),
			'pick_methods' => Yii::t('app','Pick Methods'),
			'client_identification' => Yii::t('app','Client Identification'),
			'postal_code' => Yii::t('app','Postal Code'),
			'city' => Yii::t('app','City'),
			'address' => Yii::t('app','Address'),
			'country' => Yii::t('app','Country'),
			'contact_person' => Yii::t('app','Contact Person'),
			'phone' => Yii::t('app','Phone'),
			'company_number' => Yii::t('app','Company Number'),
			'client_type_id' => Yii::t('app','Client Type'),
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

        if ($this->canView) {
            $criteria->addInCondition('id',$this->canView);
        }

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('official_title',$this->official_title,true);
		$criteria->compare('tax_number',$this->tax_number,true);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('section_id',$this->section_id);
		$criteria->compare('unloading_level_id',$this->unloading_level_id);
		$criteria->compare('pick_methods',$this->unloading_level_id,true);
		$criteria->compare('client_identification',$this->client_identification,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('contact_person',$this->contact_person,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('company_number',$this->company_number,true);
		$criteria->compare('client_type_id',$this->client_type_id);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 100
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Client the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		if ($this->isNewRecord) {
            $this->created_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : 1;
		    $this->created_dt = date('Y-m-d H:i:s');
		} else {
            $this->updated_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : 1;
		    $this->updated_dt = date('Y-m-d H:i:s');
		}
        if (!is_array($this->pick_methods)) {
            $this->pick_methods = array();
        }
        if (!in_array('SNAKE',$this->pick_methods)) {
            $pick_methods = $this->pick_methods;

           $pick_methods[] = 'SNAKE';

           $this->pick_methods = $pick_methods;
        }
        $this->pick_methods = json_encode($this->pick_methods);
		return parent::beforeSave();
	}
    public function afterSave()
    {
        $this->pick_methods = json_decode($this->pick_methods,true);
        return parent::afterSave();
    }

    public function afterFind()
    {
        $this->pick_methods = json_decode($this->pick_methods,true);
        return parent::afterFind();
    }

    public function hasPickMethod($method)
    {
        if (in_array($method,$this->pick_methods)) {
            return true;
        }
        return false;
    }


}
