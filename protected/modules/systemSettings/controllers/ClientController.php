<?php

class ClientController extends Controller
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
        if (substr($this->action->id, 0, 4) == 'ajax') {
            return array(
                array('allow')
            );
        }
        return $this->allowances;

    }

    public function actionAjaxRemoveSupplier($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $model = ClientHasSupplier::model()->findByPk($id);
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Client::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Client;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        $storage_type_ids = array();
        $section_ids = array();

        if (isset($_POST['Client'])) {
            $model->attributes = $_POST['Client'];

            if (isset($_POST['ClientHasStorageType'])) {
                $storage_type_ids = $_POST['ClientHasStorageType']['storage_type_id'];

            }
            if (isset($_POST['ClientHasSection'])) {
                $section_ids = $_POST['ClientHasSection']['section_id'];

            }
            if ($model->save()) {


                foreach ($storage_type_ids as $storage_type_id) {
                    $client_has_storage_type = new ClientHasStorageType;
                    $client_has_storage_type->client_id = $model->id;
                    $client_has_storage_type->storage_type_id = $storage_type_id;
                   $client_has_storage_type->save();
                }

                foreach ($section_ids as $section_id) {
                    $client_has_section = new ClientHasSection;
                    $client_has_section->client_id = $model->id;
                    $client_has_section->section_id = $section_id;
                    $client_has_section->save();
                }

                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'storage_type_ids' => $storage_type_ids,
            'section_ids' => $section_ids
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        if (!$this->user->canUpdate($id)) {
            throw new CHttpException(403,Yii::t('app','You are not authorized to perform this action.'));
        }
        $model = $this->loadModel($id);

        $storage_type_ids = Yii::app()->db->createCommand('SELECT storage_type_id FROM client_has_storage_type WHERE client_id = ' . $id)->queryColumn();
        $section_ids = Yii::app()->db->createCommand('SELECT section_id FROM client_has_section WHERE client_id = ' . $id)->queryColumn();

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Client'])) {
            $model->attributes = $_POST['Client'];
            if (isset($_POST['ClientHasStorageType'])) {
                $storage_type_ids = $_POST['ClientHasStorageType']['storage_type_id'];

            } else {
                $storage_type_ids = array();

            }
            if (isset($_POST['ClientHasSection'])) {
                $section_ids = $_POST['ClientHasSection']['section_id'];

            } else {
                $section_ids = array();

            }
            if ($model->save()) {
                Yii::app()->db->createCommand('DELETE FROM client_has_storage_type WHERE client_id = ' . $model->id)->execute();
                foreach ($storage_type_ids as $storage_type_id) {
                    $client_has_storage_type = new ClientHasStorageType;
                    $client_has_storage_type->client_id = $model->id;
                    $client_has_storage_type->storage_type_id = $storage_type_id;
                    $client_has_storage_type->save();
                }
                Yii::app()->db->createCommand('DELETE FROM client_has_section WHERE client_id = ' . $model->id)->execute();
                foreach ($section_ids as $section_id) {
                    $client_has_section = new ClientHasSection;
                    $client_has_section->client_id = $model->id;
                    $client_has_section->section_id = $section_id;
                    $client_has_section->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
                $this->redirect(array('update','id'=>$model->id,'tab'=>0));
            }
        }


        if (isset($_POST['ClientHasBuyer'])) {
            $client_has_supplier = new ClientHasSupplier;
            $client_has_supplier->attributes = $_POST['ClientHasBuyer'];
            $client_has_supplier->save();
            $this->redirect(array('update','id'=>$model->id,'tab'=>1));
        }
        if (isset($_POST['ClientHasSupplier'])) {
            $client_has_supplier = new ClientHasSupplier;
            $client_has_supplier->attributes = $_POST['ClientHasSupplier'];
            $client_has_supplier->save();
            $this->redirect(array('update','id'=>$model->id,'tab'=>2));
        }

        $client_has_buyers = new ClientHasSupplier('search');
        $client_has_buyers->unsetAttributes();
        $client_has_buyers->supplier_id = $id;
        $client_has_suppliers = new ClientHasSupplier('search');
        $client_has_suppliers->unsetAttributes();
        $client_has_suppliers->client_id = $id;

        $this->render('update', array(
            'model' => $model,
            'client_has_buyers' => $client_has_buyers,
            'client_has_suppliers' => $client_has_suppliers,
            'storage_type_ids' => $storage_type_ids,
            'section_ids' => $section_ids
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (!$this->user->canDelete($id)) {
            throw new CHttpException(403,Yii::t('app','You are not authorized to perform this action.'));
        }
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
    public function actionIndex()
    {
        $model = new Client('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Client'])) {
            $model->attributes = $_GET['Client'];
        }
        if ($this->user->global_client == 0) {
            $model->canView = $this->user->canView;
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'client-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
