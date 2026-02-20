<?php

class GateController extends Controller
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
        return $this->allowances;
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
        $model = Gate::model()->findByPk($id);
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
        $model = new Gate;

        $section_ids = array();

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Gate'])) {
            $model->attributes = $_POST['Gate'];

            if (isset($_POST['GateHasSection'])) {
                $section_ids = $_POST['GateHasSection']['section_id'];
            }

            if ($model->save()) {

                Yii::app()->db->createCommand('DELETE FROM gate_has_section WHERE gate_id = ' . $model->id)->execute();
                foreach ($section_ids as $section_id) {
                    $gate_has_section = new GateHasSection;
                    $gate_has_section->gate_id = $model->id;
                    $gate_has_section->section_id = $section_id;
                    $gate_has_section->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Created'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
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
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        $section_ids = Yii::app()->db->createCommand('SELECT section_id FROM gate_has_section WHERE gate_id = ' . $id)->queryColumn();
        if (isset($_POST['Gate'])) {
            $model->attributes = $_POST['Gate'];
            if (isset($_POST['GateHasSection'])) {
                $section_ids = $_POST['GateHasSection']['section_id'];

            } else {
                $section_ids = array();

            }
            if ($model->save()) {
                Yii::app()->db->createCommand('DELETE FROM gate_has_section WHERE gate_id = ' . $model->id)->execute();
                foreach ($section_ids as $section_id) {
                    $gate_has_section = new GateHasSection;
                    $gate_has_section->gate_id = $model->id;
                    $gate_has_section->section_id = $section_id;
                    $gate_has_section->save();

                }
                Yii::app()->user->setFlash('success', Yii::t('app', 'Saved'));
            }
        }

        $this->render('update', array(
            'model' => $model,
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
        $model = new Gate('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Gate']))
            $model->attributes = $_GET['Gate'];

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gate-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
