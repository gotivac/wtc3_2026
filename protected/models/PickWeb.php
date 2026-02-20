<?php

/**
 * This is the model class for table "pick_web".
 *
 * The followings are the available columns in table 'pick_web':
 * @property integer $id
 * @property integer $web_order_id
 * @property integer $web_order_product_id
 * @property integer $sloc_id
 * @property string $sloc_code
 * @property integer $activity_palett_id
 * @property string $sscc_source
 * @property integer $product_id
 * @property string $product_barcode
 * @property integer $target
 * @property integer $quantity
 * @property integer $status
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 *
 * The followings are the available model relations:
 * @property WebOrder $webOrder
 * @property WebOrderProduct $webOrderProduct
 */
class PickWeb extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pick_web';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, target', 'required'),
			array('web_order_id, web_order_product_id, sloc_id, activity_palett_id, product_id, quantity, status, created_user_id, updated_user_id', 'numerical', 'integerOnly'=>true),
			array('sloc_code, sscc_source, product_barcode', 'length', 'max'=>255),
			array('created_dt, updated_dt', 'safe'),
            array('quantity', 'numerical', 'integerOnly'=>true, 'min'=>0),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, web_order_id, web_order_product_id, sloc_id, sloc_code, activity_palett_id, sscc_source, product_id, product_barcode, target, quantity, status, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on'=>'search'),
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
			'webOrder' => array(self::BELONGS_TO, 'WebOrder', 'web_order_id'),
			'webOrderProduct' => array(self::BELONGS_TO, 'WebOrderProduct', 'web_order_product_id'),
            'product'  => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'web_order_id' => Yii::t('app','Web Order'),
			'web_order_product_id' => Yii::t('app','Web Order Product'),
			'sloc_id' => Yii::t('app','Sloc'),
			'sloc_code' => Yii::t('app','Sloc Code'),
			'activity_palett_id' => Yii::t('app','Activity Palett'),
			'sscc_source' => Yii::t('app','Sa Palete'),
			'product_id' => Yii::t('app','Product'),
			'product_barcode' => Yii::t('app','Product Barcode'),
			'quantity' => Yii::t('app','Quantity'),
			'target' => Yii::t('app','Target'),
			'status' => Yii::t('app','Status'),
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
		$criteria->compare('web_order_id',$this->web_order_id);
		$criteria->compare('web_order_product_id',$this->web_order_product_id);
		$criteria->compare('sloc_id',$this->sloc_id);
		$criteria->compare('sloc_code',$this->sloc_code,true);
		$criteria->compare('activity_palett_id',$this->activity_palett_id);
		$criteria->compare('sscc_source',$this->sscc_source,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_barcode',$this->product_barcode,true);
		$criteria->compare('target',$this->target);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('status',$this->status);


        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 1000),
            'sort' => array('defaultOrder'=>'id ASC')
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PickWeb the static model class
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

        $this->product_barcode = trim($this->product_barcode);

		return parent::beforeSave();
	}

    public function beforeDelete()
    {
        $copy = $this;
        $copy->scenario = 'delete';
        Yii::app()->Helpers->saveLog($copy);

        return parent::beforeDelete();
    }

    public function afterSave()
    {
        Yii::app()->Helpers->saveLog($this);

        return parent::afterSave();
    }


    public static function snakeSorting($picking_list)
    {


        if (empty($picking_list)) {
            return array();
        }
        $w = array();
        $s = array();

        foreach ($picking_list as $item) {
            if (substr($item['sloc_code'],0,1) == 'W') {
                $w[] = $item;
            } else {
                $s[] = $item;
            }
        }

        $sloc_code = array_column($w, 'sloc_code');
        array_multisort($sloc_code, SORT_ASC, $w);

        $old_street = (int)substr($picking_list[0]['sloc_code'], 5, 2);
        $sorted_picking_list = array();
        $picking_list_slice = array();

        foreach ($w as $item) {

            $street = (int)substr($item['sloc_code'], 5, 2);

            if ($street != $old_street) {

                $sloc_code = array_column($picking_list_slice, 'sloc_code');



                if ($old_street % 2 == 0) {
                    array_multisort($sloc_code, SORT_ASC, $picking_list_slice);
                } else {
                    array_multisort($sloc_code, SORT_DESC, $picking_list_slice);
                }

                $sorted_picking_list = array_merge($sorted_picking_list, $picking_list_slice);
                $picking_list_slice = array($item);
                $old_street = $street;
            } else {
                $picking_list_slice[] = $item;
            }

        }
        /** SORT LAST SLICE */


        $sloc_code = array_column($picking_list_slice, 'sloc_code');
        if (isset($street) && $street % 2 == 0) {
            array_multisort($sloc_code, SORT_ASC, $picking_list_slice);
        } else {

            array_multisort($sloc_code, SORT_DESC, $picking_list_slice);
        }
        /** END SORT LAST SLICE */

        $picking_list = array_merge($sorted_picking_list, $picking_list_slice);

        $complete_picking_list = array_merge($picking_list,$s);

        return $complete_picking_list;
    }
}
