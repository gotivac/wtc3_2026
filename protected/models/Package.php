<?php

/**
 * This is the model class for table "package".
 *
 * The followings are the available columns in table 'package':
 * @property integer $id
 * @property string $title
 * @property string $material
 * @property string $width
 * @property string $length
 * @property string $height
 * @property string $gross_weight
 * @property integer $product_count
 * @property integer $load_carrier_count
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property ProductHasPackage[] $productHasPackages
 */
class Package extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package';
	}

    public function getConcatened()

    {

        return $this->title.' ('.$this->product_count.Yii::t('app',' pcs in pack, ').$this->load_carrier_count.Yii::t('app',' packs on load carrier').')';

    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, product_count, load_carrier_count', 'required'),
			array('title', 'unique'),
			array('product_count, load_carrier_count, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('title, material', 'length', 'max'=>255),
			array('width, length, height, gross_weight', 'length', 'max'=>10),
			array('created_dt, updated_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, material, width, length, height, gross_weight, product_count, load_carrier_count, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'productHasPackages' => array(self::HAS_MANY, 'ProductHasPackage', 'package_id'),
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
			'material' => Yii::t('app','Material'),
			'width' => Yii::t('app','Width'),
			'length' => Yii::t('app','Length'),
			'height' => Yii::t('app','Height'),
			'gross_weight' => Yii::t('app','Gross Weight'),
			'product_count' => Yii::t('app','Product Count'),
			'load_carrier_count' => Yii::t('app','Load Carrier Count'),
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
		$criteria->compare('material',$this->material,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('gross_weight',$this->gross_weight,true);
		$criteria->compare('product_count',$this->product_count);
		$criteria->compare('load_carrier_count',$this->load_carrier_count);
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
	 * @return Package the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
        if ($this->isNewRecord) {
            $this->created_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : null;
            $this->created_dt = date('Y-m-d H:i:s');
        } else {
            $this->updated_user_id = isset(Yii::app()->user) ? Yii::app()->user->id : null;
            $this->updated_dt = date('Y-m-d H:i:s');
        }

        if ($this->width == '') {
            $this->width = null;
        }
        if ($this->length == '') {
            $this->length = null;
        }
        if ($this->height == '') {
            $this->height = null;
        }
        if ($this->gross_weight == '') {
            $this->gross_weight = null;
        }
		return parent::beforeSave();
	}

    public function afterFind()
    {
        $this->width = round($this->width);
        $this->height = round($this->height);
        $this->length = round($this->length);

        return parent::afterFind();
    }


}
