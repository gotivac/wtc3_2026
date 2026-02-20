<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 *
 * @property string $name
 * @property integer $location_id
 * @property string $email
 * @property string $password
 * @property string $roles
 * @property integer $global_client
 * @property string $notes
 * @property string $session_start
 * @property integer $code
 * @property integer $active
 * @property integer $created_user_id
 * @property string $created_dt
 * @property integer $updated_user_id
 * @property string $updated_dt
 */
class User extends CActiveRecord
{

    public $client_actions = array(
        'view',
        'create',
        'update',
        'delete',
    );

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email, password, roles', 'required'),
            array('global_client, code, location_id, active, created_user_id, updated_user_id', 'numerical', 'integerOnly' => true),
            array('name, email, password, roles', 'length', 'max' => 255),
            array('session_start, notes, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, location_id, name, email, password, roles, global_sclient, notes, session_start, code, active, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'userHasClient' => array(self::HAS_MANY, 'UserHasClient', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'location_id' => Yii::t('app', 'Location'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'roles' => Yii::t('app', 'Role'),
            'global_client' => Yii::t('app', 'All Clients'),
            'notes' => Yii::t('app', 'Notes'),
            'session_start' => Yii::t('app', 'Session Start'),
            'code' => Yii::t('app', 'Code'),
            'active' => Yii::t('app', 'Active'),
            'created_user_id' => Yii::t('app', 'Created User'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'updated_user_id' => Yii::t('app', 'Updated User'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('global_client', $this->global_client);
        $criteria->compare('roles', $this->roles, true);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('session_start', $this->session_start, true);
        $criteria->compare('code', $this->code);
        $criteria->compare('active', $this->active);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created_dt', $this->created_dt, true);
        $criteria->compare('updated_user_id', $this->updated_user_id);
        $criteria->compare('updated_dt', $this->updated_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_user_id = Yii::app()->user->id;
            $this->created_dt = date('Y-m-d H:i:s');
            $this->password = md5($this->password);
        } else {
            $this->updated_user_id = Yii::app()->user->id;
            $this->updated_dt = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    public function getAuthRole()
    {
        Yii::app()->getModule('rbac');

        return AuthRole::model()->findByAttributes(array('lower_case' => $this->roles));
    }


    public function getClients()
    {
        $clients = UserHasClient::model()->findAllByAttributes(array('user_id' => $this->id));
        $actions = $this->client_actions;
        $rights = array();
        foreach ($clients as $client) {
            foreach ($actions as $action) {
                $rights[$client->client->title][$action] = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $client->id, 'action' => $action));
            }
        }
        return $rights;
    }

    public function getCanView()
    {
        return UserHasClient::model()->getClientIds($this->id);
    }

    public function canCreate($client_id)
    {
        if ($this->roles == 'superadministrator') {
            return true;
        }

        return UserHasClientAction::model()->findByAttributes(array('user_id' => $this->id, 'client_id' => $client_id, 'action' => 'create'));

    }

    public function canUpdate($client_id)
    {

        if ($this->roles == 'superadministrator') {
            return true;
        }
        return UserHasClientAction::model()->findByAttributes(array('user_id' => $this->id, 'client_id' => $client_id, 'action' => 'update'));
    }

    public function canDelete($client_id)
    {
        if ($this->roles == 'superadministrator') {
            return true;
        }
        return UserHasClientAction::model()->findByAttributes(array('user_id' => $this->id, 'client_id' => $client_id, 'action' => 'delete'));
    }


    /**
     * This function returns array of Client models associatad to user that can execute action.
     * If user has global_client swithch turned on, function returns array of all Client models
     */

    public function clientsByAction($action, $section_id = false)
    {

        if ($this->global_client == 1) {
            $clients = Client::model()->findAll();
            $result = array();

            foreach ($clients as $client) {
                if ($section_id) {
                    if ($client->section_id != $section_id) {
                        continue;
                    }

                }
                array_push($result, $client);
            }
            return $result;
        } else {
            $result = array();

            foreach ($this->userHasClient as $user_has_client) {
                $func = 'can' . $action;

                if ($this->$func($user_has_client->client->id)) {
                    if ($section_id) {
                        if ($user_has_client->client->section_id != $section_id) {
                            continue;
                        }
                    }
                    array_push($result, $user_has_client->client);
                }
            }

            return $result;
        }
    }

    /**
     *  This function returns list of activity types allowed by user role
     */
    public function getAllowedActivityTypes($limit = false)
    {
        Yii::app()->getModule('rbac');

        if (Yii::app()->user->roles == 'superadministrator') {
            $result = ActivityType::model()->findAll();
        } else {
            $result = array();
            $role = AuthRole::model()->findByAttributes(array('lower_case' => Yii::app()->user->roles));

            foreach ($role->authRoleActivityTypes as $activity_type) {
                array_push($result, $activity_type->activityType);
            }
        }
        if ($limit) {
            return array_slice($result, 0, $limit);
        } else {
            return $result;
        }

    }


}
