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
 * @property string $session_key
 * @property integer $code
 * @property integer $active
 * @property string $rf_access
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
            array('session_start, session_key, rf_access, notes, created_dt, updated_dt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, location_id, name, email, password, roles, rf_access, global_sclient, notes, session_start, session_key, code, active, created_user_id, created_dt, updated_user_id, updated_dt', 'safe', 'on' => 'search'),
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
            'rf_access' => Yii::t('app', 'RF pristup'),
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
        $criteria->compare('rf_access', $this->rf_access);
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

        $this->rf_access = !empty($this->rf_access) ? json_encode($this->rf_access) : null;
        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->rf_access = json_decode($this->rf_access,true);
        return parent::afterSave();
    }
    public function afterFind()
    {
        $this->rf_access = json_decode($this->rf_access,true);
        return parent::afterFind();
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

    public function isAdmin()
    {
        if ($this->roles == 'administrator' || $this->roles == 'superadministrator') {
            return true;
        }
        return false;
    }

    public function getStatsOrdersInbound($from, $to)
    {


        $sql = 'SELECT activity_palett_id FROM accept JOIN activity_palett ON accept.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND accept.created_user_id = ' . $this->id . " AND accept.created_dt BETWEEN '" . $from . "' AND '" . $to . "'";

        $activity_palett_ids = Yii::app()->db->createCommand($sql)->queryColumn();

        if (!empty($activity_palett_ids)) {
            $sql = 'SELECT DISTINCT activity_order_id FROM activity_palett WHERE id IN (' . implode(',', $activity_palett_ids) . ')';
            $activity_order_ids = Yii::app()->db->createCommand($sql)->queryColumn();
            if (!empty($activity_order_ids)) {
                return count($activity_order_ids);
            }
        }
        return 0;

    }

    public function getStatsPalettsInbound($from, $to)
    {

        $sql = 'SELECT DISTINCT activity_palett_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND activity_palett_has_product.created_user_id = ' . $this->id . " AND activity_palett_has_product.created_dt BETWEEN '" . $from . "' AND '" . $to . "'";
        return count(Yii::app()->db->createCommand($sql)->queryColumn());
    }

    public function getStatsProductsInbound($from, $to)
    {
        $sql = 'SELECT DISTINCT activity_palett_has_product.product_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND (activity.system_acceptance = 1 OR activity.truck_dispatch_datetime IS NOT NULL) AND activity_palett_has_product.created_user_id = ' . $this->id . " AND activity.system_acceptance_datetime BETWEEN '" . $from . "' AND '" . $to . "'";
        $result = Yii::app()->db->createCommand($sql)->queryColumn();

        return empty($result) ? 0 : count($result);

    }

    public function getStatsPiecesInbound($from, $to)
    {
        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity ON activity_palett.activity_id = activity.id 
                WHERE activity.direction = "in" AND (activity.system_acceptance = 1 OR activity.truck_dispatch_datetime IS NOT NULL) AND activity_palett_has_product.created_user_id = ' . $this->id . " AND activity.system_acceptance_datetime BETWEEN '" . $from . "' AND '" . $to . "'";
        $result = Yii::app()->db->createCommand($sql)->queryScalar();

        return $result != NULL ? $result : 0;
    }

    public function getStatsLocatedInbound($from, $to)
    {
        $sql = "SELECT COUNT(*) FROM accept WHERE status=1 AND updated_user_id = " . $this->id . " AND updated_dt BETWEEN '" . $from . "' AND '" . $to . "'";
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        return $result != NULL ? $result : 0;

    }

    public function getStatsOrdersOutbound($from, $to)
    {
        $sql = 'SELECT DISTINCT activity_order_id FROM activity_palett JOIN activity_palett_has_product ON activity_palett.id = activity_palett_has_product.activity_palett_id JOIN activity ON activity_palett.activity_id = activity.id 
    WHERE activity.direction="out" AND activity_palett_has_product.created_user_id=' . $this->id . " AND activity_palett_has_product.created_dt BETWEEN '" . $from . "' AND '" . $to . "'";
        $result = Yii::app()->db->createCommand($sql)->queryColumn();
        if ($result) {
            return count($result);
        } else {
            return 0;
        }
    }

    public function getStatsProductsOutbound($from, $to)
    {
        $sql = 'SELECT DISTINCT product_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
    AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out") 
    AND activity_palett_has_product.created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if ($result) {
            return count($result);
        } else {
            return 0;
        }

    }

    public function getStatsPiecesOutbound($from, $to)
    {
        $sql = 'SELECT SUM(activity_palett_has_product.quantity) FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
    AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out") 
    AND activity_palett_has_product.created_user_id = ' . $this->id;

        $result = Yii::app()->db->createCommand($sql)->queryScalar();

        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function getStatsPalettsOutbound($from, $to)
    {
        $sql = 'SELECT DISTINCT activity_palett_has_product.activity_palett_id FROM activity_palett_has_product 
    JOIN activity_palett ON activity_palett_has_product.activity_palett_id = activity_palett.id 
    JOIN activity_order ON activity_palett.activity_order_id = activity_order.id 
    WHERE activity_palett_has_product.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
    AND activity_order.activity_id IN (SELECT id FROM activity WHERE direction="out") 
    AND activity_palett_has_product.created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if ($result) {
            return count($result);
        } else {
            return 0;
        }

    }

    public function getStatsOrdersWeb($from, $to)
    {
        $sql = 'SELECT COUNT(*) FROM web_order WHERE status=1 AND updated_dt BETWEEN "' . $from . '" AND "' . $to . '" AND updated_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        return $result != NULL ? $result : 0;
    }

    public function getStatsProductsWeb($from, $to)
    {
        $sql = 'SELECT DISTINCT product_id FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id 
        WHERE  pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
        AND pick_web.created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if ($result) {
            return count($result);
        } else {
            return 0;
        }
    }

    public function getStatsPcsWeb($from, $to)
    {
        $sql ='SELECT SUM(quantity) FROM pick_web JOIN web_order ON pick_web.web_order_id = web_order.id 
    WHERE pick_web.created_dt BETWEEN "' . $from . '" AND "' . $to . '" 
    AND pick_web.created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        return $result != NULL ? $result : 0;
    }

    public function getStatsControlledOrders($from, $to)
    {
        $sql = 'SELECT DISTINCT activity_order_id FROM activity_order_control WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if ($result) {
            return count($result);
        } else {
            return 0;
        }
    }

    public function getStatsControlledProducts($from, $to)
    {
        $sql = 'SELECT COUNT(*) FROM activity_order_control WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        return $result != NULL ? $result : 0;
    }

    public function getStatsControlledPcs($from, $to)
    {
        $sql = 'SELECT SUM(quantity) FROM activity_order_control WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND created_user_id = ' . $this->id;
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        return $result != NULL ? $result : 0;
    }

    public function getStatsWebFill($from,$to)
    {
        $sql = 'SELECT SUM(quantity) FROM sloc_has_product WHERE created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND created_user_id = ' . $this->id;
        $initial = Yii::app()->db->createCommand($sql)->queryScalar();
        $initial =  $initial != NULL ? $initial : 0;

        $sql = 'SELECT SUM(quantity) FROM sloc_has_product_log  WHERE reason = "Dopuna" AND created_dt BETWEEN "' . $from . '" AND "' . $to . '" AND created_user_id = ' . $this->id;
        $fill = Yii::app()->db->createCommand($sql)->queryScalar();
        $fill =  $fill != NULL ? $fill : 0;

        return $initial + $fill;
    }
}
