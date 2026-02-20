<?php

class UserController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {

        if ($this->action->id == 'passwordself' or substr($this->action->id, 0, 4) == 'ajax') {
            return array(
                array('allow')
            );
        }
        return array_merge($this->allowances, array('allow', 'passwordself', Yii::app()->user->roles));
    }

    public function actionAjaxClientRight()
    {
        if (Yii::app()->request->isPostRequest) {

            $data = $_POST;
            if ($data['checked'] == 'true') {
                $user_has_client = UserHasClient::model()->findByPk($data['id']);
                if ($user_has_client) {
                    $model = new UserHasClientAction;
                    $model->attributes = array(
                        'user_has_client_id' => $data['id'],
                        'action' => $data['action'],
                        'user_id' => $user_has_client->user_id,
                        'client_id' => $user_has_client->client_id,

                    );
                    if ($model->save()) {
                        echo "S";
                    }
                } else {
                    echo 'error';
                }

            } else {
                $model = UserHasClientAction::model()->findByAttributes(array('user_has_client_id' => $data['id'], 'action' => $data['action']));
                if ($model) {
                    if ($model->delete()) {
                        echo "D";
                    }
                }
            }


        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

    }

    public function actionAjaxRemoveClient($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $model = UserHasClient::model()->findByPk($id);
            if ($model) {
                $model->delete();
            }

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

    }


    public function actionPassword($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['Password'])) {

            $newPassword = $_POST['Password']['password'];
            $cnfPassword = $_POST['Password']['confirm'];

            if ($newPassword !== $cnfPassword) {
                Yii::app()->user->setFlash('error', Yii::t('app', 'You have to confirm password.'));
            } else {
                $model->password = md5($newPassword);
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Password changed.'));
                    $this->redirect(array('update', 'id' => $model->id));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'Error.'));
                }
            }
        }
        $this->render('password', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionPasswordself()
    {
        $model = $this->loadModel(Yii::app()->user->id);
        if (isset($_POST['Password'])) {

            $newPassword = $_POST['Password']['password'];
            $cnfPassword = $_POST['Password']['confirm'];
            if ($newPassword !== $cnfPassword) {
                Yii::app()->user->setFlash('error', Yii::t('app', 'You have to confirm password.'));
            } else {
                $model->password = md5($newPassword);
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Password changed.'));
                    $this->redirect(array('passwordself'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'Error'));

                }
            }
        }
        $this->render('password', array('model' => $model));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];


            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {


            $model->attributes = $_POST['User'];
            if ($_POST['Password']['password'] != '') {
                $model->password = md5($_POST['Password']['password']);
            }
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
            }
            $this->redirect(array('update','id'=>$model->id));
        }

        if (isset($_POST['UserHasClientAction'])) {
            $user_has_client = new UserHasClient;
            $user_has_client->attributes = $_POST['UserHasClient'];
            if ($user_has_client->save()) {
                foreach ($_POST['UserHasClientAction'] as $action => $v) {
                    $user_has_client_action = new UserHasClientAction;
                    $user_has_client_action->attributes = array(
                        'user_has_client_id' => $user_has_client->id,
                        'action' => $action
                    );
                    $user_has_client_action->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
            } else {
                Yii::app()->user->setFlash('error', CHtml::errorSummary($user_has_client));
            }
            $this->redirect(array('update','id'=>$model->id,'tab'=>1));
        }


        $user_has_clients = new UserHasClient('search');
        $user_has_clients->unsetAttributes();
        $user_has_clients->user_id = $id;


        $this->render('update', array(
            'model' => $model,
            'user_has_clients' => $user_has_clients
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public
    function actionDelete($id)
    {

        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public
    function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }


        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
