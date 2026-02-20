<?php

/**
 * This is the model class for table "sloc".
 *
 * The followings are the available columns in table 'sloc':
 * @property integer $id
 * @property integer $sloc_type_id
 * @property integer $section_id
 * @property integer $location_id
 * @property string $sloc_code
 * @property string $sloc_street
 * @property integer $sloc_field
 * @property integer $sloc_position
 * @property string $sloc_vertical
 * @property integer $reserved_product_id
 * @property string $reserved_product_barcode
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property Section $section
 * @property SlocType $slocType
 */
class Sloc extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sloc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sloc_type_id, section_id, sloc_code', 'required'),
			array('sloc_code', 'unique'),
			array('sloc_type_id, section_id, location_id, sloc_field, sloc_position, reserved_product_id, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('sloc_code, sloc_vertical, sloc_street, reserved_product_barcode', 'length', 'max'=>255),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sloc_type_id, location_id, section_id, sloc_code, sloc_street, sloc_field, sloc_position, sloc_vertical, reserved_product_id, reserved_product_barcode, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'section' => array(self::BELONGS_TO, 'Section', 'section_id'),
			'slocType' => array(self::BELONGS_TO, 'SlocType', 'sloc_type_id'),
            'hasActivityPalett' => array(self::HAS_ONE,"SlocHasActivityPalett",'sloc_id'),
            'hasActivityPaletts' => array(self::HAS_MANY,"SlocHasActivityPalett",'sloc_id'),
            'hasProducts' => array(self::HAS_MANY,"SlocHasProduct",'sloc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'sloc_type_id' => Yii::t('app','Sloc Type'),
			'section_id' => Yii::t('app','Section'),
			'location_id' => Yii::t('app','Location'),
			'sloc_code' => Yii::t('app','Sloc Code'),
			'sloc_street' => Yii::t('app','Sloc Street'),
			'sloc_field' => Yii::t('app','Sloc Field'),
			'sloc_position' => Yii::t('app','Sloc Position'),
			'sloc_vertical' => Yii::t('app','Sloc Vertical'),
			'reserved_product_id' => Yii::t('app','Reserved For Product'),
			'reserved_product_barcode' => Yii::t('app','Reserved For Product Barcode'),
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
		$criteria->compare('sloc_type_id',$this->sloc_type_id);
		$criteria->compare('section_id',$this->section_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('sloc_code',$this->sloc_code,true);
		$criteria->compare('sloc_street',$this->sloc_street,true);
		$criteria->compare('sloc_field',$this->sloc_field);
		$criteria->compare('sloc_position',$this->sloc_position);
		$criteria->compare('sloc_vertical',$this->sloc_vertical,true);
		$criteria->compare('reserved_product_id',$this->reserved_product_id);
		$criteria->compare('reserved_product_barcode',$this->reserved_product_barcode,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_dt',$this->created_dt,true);
		$criteria->compare('updated_user_id',$this->updated_user_id);
		$criteria->compare('updated_dt',$this->updated_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 100,
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sloc the static model class
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
        if ($this->location_id == null && $this->section) {
            $location = Location::model()->findByPk($this->section->location_id);
            $this->location_id = $location->id;
        }

        $this->reserved_product_barcode = null;
        if ($this->reserved_product_id) {
            $reserved_product = Product::model()->findByPk($this->reserved_product_id);
            if ($reserved_product) {
                $this->reserved_product_barcode = $reserved_product->product_barcode;
            }
        } else {
            $this->reserved_product_id = null;
        }
		return parent::beforeSave();
	}

    public function getReservedProduct()
    {
        return Product::model()->findByPk($this->reserved_product_id);
    }

    public function afterSave()
    {
        Yii::app()->Helpers->saveLog($this);
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

}
