<?php

/**
 * This is the model class for table "change_log".
 *
 * The followings are the available columns in table 'change_log':
 * @property integer $id
 * @property string $model_name
 * @property string $scenario
 * @property integer $model_id
 * @property string $data
 * @property integer $created_user_id
 * @property string $created_dt
 */
class ChangeLog extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'change_log';
        
    }
    
    public $author_search;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('model_name, scenario', 'length', 'max' => 255),
            array('data, created_dt, author_search', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, model_name, scenario, model_id, data, author_search, created_user_id, created_dt', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
           'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'model_name' => Yii::t('app', 'Model'),
            
            'scenario' => Yii::t('app', 'Scenario'),
            'model_id' => Yii::t('app', 'Model ID'),
            'data' => Yii::t('app', 'Data'),
            'created_user_id' => Yii::t('app', 'User'),
            'created_dt' => Yii::t('app', 'Created'),
            'author_search' => Yii::t('app', 'User'),
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('model_name', $this->model_name, true);
        $criteria->compare('user.name', $this->author_search, true);
        $criteria->compare('scenario', $this->scenario, true);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('data', $this->data, true);
        /*
        $criteria->compare('created_user_id', $this->created_user_id);
         */ 
        $criteria->compare('t.created_dt', $this->created_dt, true);
         
        $criteria->with = array('user');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
                'attributes' => array(
                    'author_search' => array(
                        'asc' => 'user.name',
                        'desc' => 'user.name DESC',
                    ),
                    '*',
                ),
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChangeLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

}
